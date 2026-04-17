<?php

namespace Renatio\Image;

use Renatio\Image\Classes\ImageManipulation;
use System\Classes\PluginBase;

class Plugin extends PluginBase
{
    public function pluginDetails(): array
    {
        return [
            'name' => 'renatio.image::lang.plugin.name',
            'description' => 'renatio.image::lang.plugin.description',
            'author' => 'Renatio',
            'icon' => 'octo-icon-image',
        ];
    }

    public function registerMarkupTags(): array
    {
        $imageManipulation = new ImageManipulation;

        return [
            'filters' => [
                'brightness' => [$imageManipulation, 'brightness'],
                'contrast' => [$imageManipulation, 'contrast'],
                'gamma' => [$imageManipulation, 'gamma'],
                'colorize' => [$imageManipulation, 'colorize'],
                'blur' => [$imageManipulation, 'blur'],
                'pixelate' => [$imageManipulation, 'pixelate'],
                'greyscale' => [$imageManipulation, 'greyscale'],
                'sepia' => [$imageManipulation, 'sepia'],
                'sharpen' => [$imageManipulation, 'sharpen'],
                'watermark' => [$imageManipulation, 'watermark'],
                'webp' => [$imageManipulation, 'webp'],
                'optimize' => [$imageManipulation, 'optimize'],
                'base64' => [$imageManipulation, 'base64'],
                'get_width' => [$imageManipulation, 'getWidth'],
                'get_height' => [$imageManipulation, 'getHeight'],
                'quality' => [$imageManipulation, 'quality'],
            ],
        ];
    }
}
