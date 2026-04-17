<?php

use Renatio\Image\Classes\ImageManipulation;
use Renatio\Image\Plugin;
use System\Classes\PluginBase;

describe('Plugin Class', function () {
    beforeEach(function () {
        $this->plugin = new Plugin(app());
    });

    it('extends PluginBase', function () {
        expect($this->plugin)->toBeInstanceOf(PluginBase::class);
    });

    it('returns plugin details with required keys', function () {
        $details = $this->plugin->pluginDetails();

        expect($details)
            ->toBeArray()
            ->toHaveKeys(['name', 'description', 'author', 'icon'])
            ->and($details['author'])->toBe('Renatio');
    });

    it('registers all image manipulation filters', function () {
        $markupTags = $this->plugin->registerMarkupTags();

        expect($markupTags)
            ->toBeArray()
            ->toHaveKey('filters')
            ->and($markupTags['filters'])->toHaveKeys([
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
                'base64',
                'get_width',
                'get_height',
                'quality',
            ]);
    });

    it('maps filters to ImageManipulation methods', function () {
        $markupTags = $this->plugin->registerMarkupTags();
        $filters = $markupTags['filters'];

        $expectedMappings = [
            'brightness' => 'brightness',
            'contrast' => 'contrast',
            'gamma' => 'gamma',
            'colorize' => 'colorize',
            'blur' => 'blur',
            'pixelate' => 'pixelate',
            'greyscale' => 'greyscale',
            'sepia' => 'sepia',
            'sharpen' => 'sharpen',
            'watermark' => 'watermark',
            'webp' => 'webp',
            'optimize' => 'optimize',
            'base64' => 'base64',
            'get_width' => 'getWidth',
            'get_height' => 'getHeight',
            'quality' => 'quality',
        ];

        foreach ($expectedMappings as $filterName => $methodName) {
            expect($filters[$filterName])->toBeArray()
                ->and($filters[$filterName][0])->toBeInstanceOf(ImageManipulation::class)
                ->and($filters[$filterName][1])->toBe($methodName);
        }
    });
});
