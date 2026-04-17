<?php

use Renatio\Image\Classes\ImageManipulation;

describe('ImageManipulation Class', function () {
    beforeEach(function () {
        $this->imageManipulation = new ImageManipulation;
    });

    it('can be instantiated', function () {
        expect($this->imageManipulation)->toBeInstanceOf(ImageManipulation::class);
    });

    describe('null path handling', function () {
        it('returns null for manipulation methods when path is null', function (string $method, array $args) {
            $result = $this->imageManipulation->$method(null, ...$args);

            expect($result)->toBeNull();
        })->with([
            'brightness' => ['brightness', [50]],
            'contrast' => ['contrast', [1.5]],
            'gamma' => ['gamma', [2.0]],
            'colorize' => ['colorize', [50, 50, 50]],
            'blur' => ['blur', [10]],
            'pixelate' => ['pixelate', [50]],
            'greyscale' => ['greyscale', []],
            'sepia' => ['sepia', []],
            'sharpen' => ['sharpen', [10.0]],
            'watermark' => ['watermark', []],
            'webp' => ['webp', []],
            'optimize' => ['optimize', []],
            'quality' => ['quality', [80]],
            'base64' => ['base64', []],
            'getWidth' => ['getWidth', []],
            'getHeight' => ['getHeight', []],
        ]);
    });

    describe('non-existent file handling', function () {
        it('returns original path when file does not exist', function (string $method, array $args) {
            $path = '/non-existent/image.jpg';
            $result = $this->imageManipulation->$method($path, ...$args);

            expect($result)->toBe($path);
        })->with([
            'brightness' => ['brightness', [50]],
            'contrast' => ['contrast', [1.5]],
            'gamma' => ['gamma', [2.0]],
            'blur' => ['blur', [10]],
            'greyscale' => ['greyscale', []],
            'sepia' => ['sepia', []],
            'quality' => ['quality', [80]],
        ]);

        it('returns null for base64 when file does not exist', function () {
            $result = $this->imageManipulation->base64('/non-existent/image.jpg');

            expect($result)->toBeNull();
        });

        it('returns null for getWidth when file does not exist', function () {
            $result = $this->imageManipulation->getWidth('/non-existent/image.jpg');

            expect($result)->toBeNull();
        });

        it('returns null for getHeight when file does not exist', function () {
            $result = $this->imageManipulation->getHeight('/non-existent/image.jpg');

            expect($result)->toBeNull();
        });
    });

    describe('input validation', function () {
        it('throws exception for invalid brightness', function (int $value) {
            expect(fn () => $this->imageManipulation->brightness('/test.jpg', $value))
                ->toThrow(InvalidArgumentException::class);
        })->with([
            'below minimum' => [-101],
            'above maximum' => [101],
        ]);

        it('throws exception for invalid contrast', function (float $value) {
            expect(fn () => $this->imageManipulation->contrast('/test.jpg', $value))
                ->toThrow(InvalidArgumentException::class);
        })->with([
            'below minimum' => [-101],
            'above maximum' => [101],
        ]);

        it('throws exception for invalid gamma', function (float $value) {
            expect(fn () => $this->imageManipulation->gamma('/test.jpg', $value))
                ->toThrow(InvalidArgumentException::class);
        })->with([
            'below minimum' => [0.05],
            'above maximum' => [10.0],
        ]);

        it('throws exception for invalid colorize values', function (int $r, int $g, int $b) {
            expect(fn () => $this->imageManipulation->colorize('/test.jpg', $r, $g, $b))
                ->toThrow(InvalidArgumentException::class);
        })->with([
            'red too high' => [101, 0, 0],
            'green too low' => [0, -101, 0],
            'blue too high' => [0, 0, 101],
        ]);

        it('throws exception for invalid blur', function (int $value) {
            expect(fn () => $this->imageManipulation->blur('/test.jpg', $value))
                ->toThrow(InvalidArgumentException::class);
        })->with([
            'negative' => [-1],
            'above maximum' => [101],
        ]);

        it('throws exception for invalid pixelate', function (int $value) {
            expect(fn () => $this->imageManipulation->pixelate('/test.jpg', $value))
                ->toThrow(InvalidArgumentException::class);
        })->with([
            'zero' => [0],
            'above maximum' => [1001],
        ]);

        it('throws exception for invalid sharpen', function (float $value) {
            expect(fn () => $this->imageManipulation->sharpen('/test.jpg', $value))
                ->toThrow(InvalidArgumentException::class);
        })->with([
            'negative' => [-1],
            'above maximum' => [101],
        ]);

        it('throws exception for invalid quality', function (int $value) {
            expect(fn () => $this->imageManipulation->quality('/test.jpg', $value))
                ->toThrow(InvalidArgumentException::class);
        })->with([
            'negative' => [-1],
            'above maximum' => [101],
        ]);

        it('accepts valid boundary values', function (string $method, array $args) {
            expect(fn () => $this->imageManipulation->$method('/test.jpg', ...$args))
                ->not->toThrow(InvalidArgumentException::class);
        })->with([
            'brightness min' => ['brightness', [-100]],
            'brightness max' => ['brightness', [100]],
            'contrast min' => ['contrast', [-100]],
            'contrast max' => ['contrast', [100]],
            'gamma min' => ['gamma', [0.1]],
            'gamma max' => ['gamma', [9.99]],
            'blur min' => ['blur', [0]],
            'blur max' => ['blur', [100]],
            'pixelate min' => ['pixelate', [1]],
            'pixelate max' => ['pixelate', [1000]],
            'sharpen min' => ['sharpen', [0]],
            'sharpen max' => ['sharpen', [100]],
            'quality min' => ['quality', [0]],
            'quality max' => ['quality', [100]],
        ]);

        it('includes parameter name in exception message', function () {
            try {
                $this->imageManipulation->brightness('/test.jpg', 200);
            } catch (InvalidArgumentException $e) {
                expect($e->getMessage())->toContain('brightness');
            }
        });
    });

    describe('default values', function () {
        it('pixelate defaults to 50', function () {
            $reflection = new ReflectionMethod($this->imageManipulation, 'pixelate');
            $parameters = $reflection->getParameters();

            expect($parameters[1]->getDefaultValue())->toBe(50);
        });

        it('base64 defaults to jpeg format with prefix', function () {
            $reflection = new ReflectionMethod($this->imageManipulation, 'base64');
            $parameters = $reflection->getParameters();

            expect($parameters[1]->getDefaultValue())->toBe('jpeg')
                ->and($parameters[2]->getDefaultValue())->toBeTrue();
        });
    });

    describe('return types', function () {
        it('manipulation methods return nullable string', function (string $method) {
            $reflection = new ReflectionMethod($this->imageManipulation, $method);

            expect($reflection->getReturnType()->allowsNull())->toBeTrue()
                ->and($reflection->getReturnType()->getName())->toBe('string');
        })->with([
            'brightness',
            'contrast',
            'gamma',
            'colorize',
            'blur',
            'pixelate',
            'greyscale',
            'sepia',
            'sharpen',
            'watermark',
            'webp',
            'optimize',
            'quality',
            'base64',
        ]);

        it('dimension methods return nullable int', function (string $method) {
            $reflection = new ReflectionMethod($this->imageManipulation, $method);

            expect($reflection->getReturnType()->allowsNull())->toBeTrue()
                ->and($reflection->getReturnType()->getName())->toBe('int');
        })->with(['getWidth', 'getHeight']);
    });

    describe('configuration', function () {
        it('uses default manipulations directory', function () {
            $reflection = new ReflectionMethod($this->imageManipulation, 'getManipulationsDirectory');
            $reflection->setAccessible(true);

            $result = $reflection->invoke($this->imageManipulation);

            expect($result)->toBe('storage/app/uploads/public/manipulations/');
        });

        it('uses default optimization timeout', function () {
            $reflection = new ReflectionMethod($this->imageManipulation, 'getOptimizationTimeout');
            $reflection->setAccessible(true);

            $result = $reflection->invoke($this->imageManipulation);

            expect($result)->toBe(120);
        });

        it('optimizes after manipulation by default', function () {
            $reflection = new ReflectionMethod($this->imageManipulation, 'shouldOptimizeAfterManipulation');
            $reflection->setAccessible(true);

            $result = $reflection->invoke($this->imageManipulation);

            expect($result)->toBeTrue();
        });
    });

    describe('error handling', function () {
        it('returns original path when manipulation fails', function () {
            // When file doesn't exist, method should return original path gracefully
            $result = $this->imageManipulation->brightness('/non-existent/image.jpg', 50);

            expect($result)->toBe('/non-existent/image.jpg');
        });

        it('handles invalid path gracefully', function () {
            // Path that will fail to process should return original
            $result = $this->imageManipulation->greyscale('/invalid/path/image.jpg');

            expect($result)->toBe('/invalid/path/image.jpg');
        });
    });

    describe('URL parsing', function () {
        it('extracts path from full URL', function () {
            $reflection = new ReflectionMethod($this->imageManipulation, 'setOriginalImagePath');
            $reflection->setAccessible(true);

            $pathProperty = new ReflectionProperty($this->imageManipulation, 'originalImagePath');
            $pathProperty->setAccessible(true);

            $reflection->invoke($this->imageManipulation, 'https://example.com/storage/image.jpg?v=123');

            expect($pathProperty->getValue($this->imageManipulation))->toBe('/storage/image.jpg');
        });

        it('handles path without domain', function () {
            $reflection = new ReflectionMethod($this->imageManipulation, 'setOriginalImagePath');
            $reflection->setAccessible(true);

            $pathProperty = new ReflectionProperty($this->imageManipulation, 'originalImagePath');
            $pathProperty->setAccessible(true);

            $reflection->invoke($this->imageManipulation, '/storage/image.jpg');

            expect($pathProperty->getValue($this->imageManipulation))->toBe('/storage/image.jpg');
        });

        it('sets null when path is null', function () {
            $reflection = new ReflectionMethod($this->imageManipulation, 'setOriginalImagePath');
            $reflection->setAccessible(true);

            $pathProperty = new ReflectionProperty($this->imageManipulation, 'originalImagePath');
            $pathProperty->setAccessible(true);

            $reflection->invoke($this->imageManipulation, null);

            expect($pathProperty->getValue($this->imageManipulation))->toBeNull();
        });
    });
});
