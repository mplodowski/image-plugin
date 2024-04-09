<?php

namespace Renatio\Image\Classes;

use Spatie\Image\Image;

class ImageManipulation
{
    protected string|null $originalImagePath = null;

    protected string|null $newImagePath = null;

    public function brightness(string $path, int $brightness)
    {
        return $this->handle($path, 'brightness', [$brightness]);
    }

    public function contrast(string $path, float $level)
    {
        return $this->handle($path, 'contrast', [$level]);
    }

    public function gamma(string $path, float $gamma)
    {
        return $this->handle($path, 'gamma', [$gamma]);
    }

    public function colorize(string $path, int $red, int $green, int $blue)
    {
        return $this->handle($path, 'colorize', [$red, $green, $blue]);
    }

    public function blur(string $path, int $blur)
    {
        return $this->handle($path, 'blur', [$blur]);
    }

    public function pixelate(string $path, int $pixelate = 50)
    {
        return $this->handle($path, 'pixelate', [$pixelate]);
    }

    public function greyscale(string $path)
    {
        return $this->handle($path, 'greyscale');
    }

    public function sepia(string $path)
    {
        return $this->handle($path, 'sepia');
    }

    public function sharpen(string $path, float $amount)
    {
        return $this->handle($path, 'sharpen', [$amount]);
    }

    public function watermark(string $path, string|null $watermarkImage = null, array $options = [])
    {
        // todo parse options
        $watermarkImage = $watermarkImage ?? themes_path('demo/assets/images/watermark.png');

        array_unshift($options, $watermarkImage);

        return $this->handle($path, 'watermark', $options);
    }

    public function webp($path)
    {
        return $this->handle($path, 'webp');
    }

    public function optimize($path)
    {
        return $this->handle($path, 'optimize');
    }

    // base64 - function

    // width - function

    // height - function

    // quality

//    public function width($path, $options = [])
//    {
//        $path = parse_url($path, PHP_URL_PATH);
//
//        return Image::load(base_path($path))
//            ->getWidth();
//    }
//
//    public function height($path, $options = [])
//    {
//        $path = parse_url($path, PHP_URL_PATH);
//
//        return Image::load(base_path($path))
//            ->getHeight();
//    }

    // resize ??

    // canvas ??

    protected function handle(string $path, string $manipulation, array $options = [])
    {
        $this->originalImagePath = parse_url($path, PHP_URL_PATH);

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
            'webp' => null,
            'optimize' => $image->optimize(),
        };

        // todo global setting?
//        $image->optimize();

        $image->save($this->newImagePath);

        return $this->getImageUrl();
    }

    protected function getNewFilePath(string $manipulation, array $options = [])
    {
        $dirPath = 'storage/app/uploads/public/manipulations/';

        if (! is_dir($dirPath)) {
            mkdir($dirPath);
        }

        $info = pathinfo($this->originalImagePath);

        $extension = $manipulation === 'webp' ? 'webp' : $info['extension'];

        return $dirPath.$this->getFileHash($manipulation, $options).".$extension";
    }

    protected function getImage()
    {
        return Image::load(base_path($this->originalImagePath));
    }

    protected function getImageUrl()
    {
        return asset($this->newImagePath);
    }

    protected function getFileHash(string $manipulation, array $options = [])
    {
        return md5($this->originalImagePath.$manipulation.implode($options).filemtime(base_path($this->originalImagePath)));
    }
}
