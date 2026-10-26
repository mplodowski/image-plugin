<?php

use Renatio\Image\Classes\ImageManipulation;

function optimizerBinaryPaths(?string $binaryPath): array
{
    $manipulation = new class($binaryPath) extends ImageManipulation
    {
        public function __construct(private ?string $binaryPath)
        {
        }

        protected function setting(string $key): mixed
        {
            return $key === 'binary_path' ? $this->binaryPath : parent::setting($key);
        }
    };

    $chain = (fn () => $this->buildOptimizerChain())->call($manipulation);

    return array_map(fn ($optimizer) => $optimizer->binaryPath, $chain->getOptimizers());
}

it('relies on the system PATH when IMAGE_OPTIMIZER_PATH is not set', function () {
    $original = getenv('IMAGE_OPTIMIZER_PATH');
    putenv('IMAGE_OPTIMIZER_PATH');
    unset($_ENV['IMAGE_OPTIMIZER_PATH'], $_SERVER['IMAGE_OPTIMIZER_PATH']);

    try {
        $config = require plugins_path('renatio/image/config/manipulations.php');
    } finally {
        if ($original !== false) {
            putenv("IMAGE_OPTIMIZER_PATH=$original");
            $_ENV['IMAGE_OPTIMIZER_PATH'] = $_SERVER['IMAGE_OPTIMIZER_PATH'] = $original;
        }
    }

    expect($config['binary_path'])->toBeNull();
});

it('leaves the optimizer binaries on the system PATH when no binary path is set', function () {
    expect(optimizerBinaryPaths(null))->not->toBeEmpty()->each->toBe('');
});

it('points every optimizer at the configured binary path', function () {
    expect(optimizerBinaryPaths('/usr/local/bin'))->not->toBeEmpty()->each->toBe('/usr/local/bin/');
});
