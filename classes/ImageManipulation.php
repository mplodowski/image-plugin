<?php

namespace Renatio\Image\Classes;

use Illuminate\Support\Facades\Log;
use InvalidArgumentException;
use Renatio\Image\Jobs\OptimizeImageJob;
use Renatio\Image\Models\Settings;
use Spatie\Image\Image;
use Spatie\ImageOptimizer\OptimizerChain;
use Spatie\ImageOptimizer\OptimizerChainFactory;
use Spatie\ImageOptimizer\Optimizers\Jpegoptim;

class ImageManipulation
{
    protected ?string $originalImagePath = null;

    protected ?string $newImagePath = null;

    public function brightness(?string $path, int $brightness): ?string
    {
        $this->validateRange($brightness, -100, 100, 'brightness');

        return $this->handle($path, 'brightness', [$brightness]);
    }

    public function contrast(?string $path, float $level): ?string
    {
        $this->validateRange($level, -100, 100, 'contrast');

        return $this->handle($path, 'contrast', [$level]);
    }

    public function gamma(?string $path, float $gamma): ?string
    {
        $this->validateRange($gamma, 0.1, 9.99, 'gamma');

        return $this->handle($path, 'gamma', [$gamma]);
    }

    public function colorize(?string $path, int $red, int $green, int $blue): ?string
    {
        $this->validateRange($red, -100, 100, 'red');
        $this->validateRange($green, -100, 100, 'green');
        $this->validateRange($blue, -100, 100, 'blue');

        return $this->handle($path, 'colorize', [$red, $green, $blue]);
    }

    public function blur(?string $path, int $blur): ?string
    {
        $this->validateRange($blur, 0, 100, 'blur');

        return $this->handle($path, 'blur', [$blur]);
    }

    public function pixelate(?string $path, int $pixelate = 50): ?string
    {
        $this->validateRange($pixelate, 1, 1000, 'pixelate');

        return $this->handle($path, 'pixelate', [$pixelate]);
    }

    public function greyscale(?string $path): ?string
    {
        return $this->handle($path, 'greyscale');
    }

    public function sepia(?string $path): ?string
    {
        return $this->handle($path, 'sepia');
    }

    public function sharpen(?string $path, float $amount): ?string
    {
        $this->validateRange($amount, 0, 100, 'sharpen');

        return $this->handle($path, 'sharpen', [$amount]);
    }

    public function watermark(?string $path, ?string $watermarkImage = null, array $options = []): ?string
    {
        $watermarkImage ??= $this->getDefaultWatermarkPath();

        return $this->handle($path, 'watermark', [$watermarkImage, ...$options]);
    }

    public function webp(?string $path): ?string
    {
        return $this->handle($path, 'webp');
    }

    public function optimize(?string $path): ?string
    {
        return $this->handle($path, 'optimize');
    }

    public function base64(?string $path, string $imageFormat = 'jpeg', bool $prefixWithFormat = true): ?string
    {
        $image = $this->loadOriginalImage($path);

        return $image?->base64($imageFormat, $prefixWithFormat);
    }

    public function getWidth(?string $path): ?int
    {
        return $this->loadOriginalImage($path)?->getWidth();
    }

    public function getHeight(?string $path): ?int
    {
        return $this->loadOriginalImage($path)?->getHeight();
    }

    public function getFileSize(?string $path): ?int
    {
        $this->setOriginalImagePath($path);

        if (! $this->originalImageExists()) {
            return null;
        }

        return filesize(base_path($this->originalImagePath));
    }

    public function quality(?string $path, int $quality): ?string
    {
        $this->validateRange($quality, 0, 100, 'quality');

        return $this->handle($path, 'quality', [$quality]);
    }

    protected function handle(?string $path, string $manipulation, array $options = []): ?string
    {
        try {
            $this->setOriginalImagePath($path);

            if (! $this->originalImageExists()) {
                return $path;
            }

            $this->newImagePath = $this->getNewFilePath($manipulation, $options);

            if (file_exists($this->newImagePath)) {
                return $this->getImageUrl();
            }

            $image = $this->getImage();

            match ($manipulation) {
                'brightness' => $image->brightness(...$options),
                'contrast' => $image->contrast(...$options),
                'gamma' => $image->gamma(...$options),
                'colorize' => $image->colorize(...$options),
                'blur' => $image->blur(...$options),
                'pixelate' => $image->pixelate(...$options),
                'greyscale' => $image->greyscale(),
                'sepia' => $image->sepia(),
                'sharpen' => $image->sharpen(...$options),
                'watermark' => $image->watermark(...$options),
                'optimize' => $image->optimize(),
                'quality' => $image->quality(...$options),
                'webp' => $image->format('webp'),
            };

            $image->save($this->newImagePath);

            if ($this->shouldOptimizeAfterManipulation()) {
                $this->dispatchOptimization($this->newImagePath);
            }

            return $this->getImageUrl();
        } catch (\Exception $e) {
            Log::error('Image manipulation failed', [
                'manipulation' => $manipulation,
                'path' => $path,
                'error' => $e->getMessage(),
            ]);

            return $path;
        }
    }

    protected function getNewFilePath(string $manipulation, array $options = []): string
    {
        $dirPath = $this->getManipulationsDirectory();

        if (! is_dir($dirPath)) {
            mkdir($dirPath, 0755, true);
        }

        $info = pathinfo($this->originalImagePath);

        $extension = $manipulation === 'webp' ? 'webp' : $info['extension'];

        return $dirPath.$this->getFileHash($manipulation, $options).".$extension";
    }

    protected function getImage(): Image
    {
        return Image::load(base_path($this->originalImagePath));
    }

    protected function loadOriginalImage(?string $path): ?Image
    {
        $this->setOriginalImagePath($path);

        if (! $this->originalImageExists()) {
            return null;
        }

        return $this->getImage();
    }

    protected function getImageUrl(): string
    {
        return asset($this->newImagePath);
    }

    protected function getFileHash(string $manipulation, array $options = []): string
    {
        return md5($this->buildCacheKey(
            $this->originalImagePath,
            $manipulation,
            $options,
            filemtime(base_path($this->originalImagePath)),
        ));
    }

    protected function buildCacheKey(string $path, string $manipulation, array $options, int $modifiedAt): string
    {
        return $path.'|'.$manipulation.'|'.implode('|', $options).'|'.$modifiedAt;
    }

    protected function setOriginalImagePath(?string $path): void
    {
        $this->originalImagePath = $path ? (parse_url($path, PHP_URL_PATH) ?: null) : null;
    }

    protected function originalImageExists(): bool
    {
        return $this->originalImagePath && file_exists(base_path($this->originalImagePath));
    }

    protected function validateRange(int|float $value, int|float $min, int|float $max, string $parameter): void
    {
        if ($value < $min || $value > $max) {
            throw new InvalidArgumentException(
                "The $parameter value must be between $min and $max. Given: $value",
            );
        }
    }

    public function clearCache(): int
    {
        $directory = base_path($this->getManipulationsDirectory());

        if (! is_dir($directory)) {
            return 0;
        }

        $removed = 0;

        foreach (new \FilesystemIterator($directory) as $file) {
            if ($file->isFile() && unlink($file->getPathname())) {
                $removed++;
            }
        }

        return $removed;
    }

    protected function setting(string $key): mixed
    {
        return Settings::get($key, config("renatio.image::manipulations.$key"));
    }

    protected function getManipulationsDirectory(): string
    {
        return (string) $this->setting('directory');
    }

    protected function getDefaultWatermarkPath(): string
    {
        return (string) $this->setting('watermark');
    }

    protected function shouldOptimizeAfterManipulation(): bool
    {
        return (bool) $this->setting('optimize_after_manipulation');
    }

    protected function getOptimizationTimeout(): int
    {
        return (int) $this->setting('optimization_timeout');
    }

    protected function shouldStripMetadata(): bool
    {
        return (bool) $this->setting('strip_metadata');
    }

    protected function getOptimizerQuality(): int
    {
        return (int) $this->setting('optimizer_quality');
    }

    protected function getBinaryPath(): ?string
    {
        $value = $this->setting('binary_path');

        return $value !== null && $value !== '' ? (string) $value : null;
    }

    protected function shouldQueueOptimization(): bool
    {
        return (bool) $this->setting('queue_optimization');
    }

    protected function dispatchOptimization(string $imagePath): void
    {
        if ($this->shouldQueueOptimization()) {
            OptimizeImageJob::dispatch($imagePath);

            return;
        }

        $this->runOptimizer($imagePath);
    }

    public function runOptimizer(string $imagePath): void
    {
        $this->buildOptimizerChain()->optimize($imagePath);
    }

    protected function buildOptimizerChain(): OptimizerChain
    {
        $chain = OptimizerChainFactory::create(['quality' => $this->getOptimizerQuality()])
            ->setTimeout($this->getOptimizationTimeout());

        $binaryPath = $this->getBinaryPath();
        $stripMetadata = $this->shouldStripMetadata();

        foreach ($chain->getOptimizers() as $optimizer) {
            if ($binaryPath) {
                $optimizer->setBinaryPath($binaryPath);
            }

            if (! $stripMetadata && $optimizer instanceof Jpegoptim) {
                $optimizer->setOptions(array_values(array_filter(
                    $optimizer->options,
                    fn (string $option) => $option !== '--strip-all',
                )));
            }
        }

        return $chain;
    }
}
