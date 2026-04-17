<?php

namespace Renatio\Image;

use Renatio\Image\Classes\ImageManipulation;
use Renatio\Image\Console\ClearCache;
use Renatio\Image\Models\Settings;
use System\Classes\PluginBase;
use System\Classes\SettingsManager;

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

    public function register(): void
    {
        $this->registerConsoleCommand('image:clear', ClearCache::class);
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
                'get_file_size' => [$imageManipulation, 'getFileSize'],
                'quality' => [$imageManipulation, 'quality'],
            ],
        ];
    }

    public function registerPermissions(): array
    {
        return [
            'renatio.image.access_settings' => [
                'label' => 'renatio.image::lang.permissions.settings',
                'tab' => 'renatio.image::lang.permissions.tab',
            ],
        ];
    }

    public function registerSettings(): array
    {
        return [
            'settings' => [
                'label' => 'renatio.image::lang.settings.label',
                'description' => 'renatio.image::lang.settings.description',
                'category' => SettingsManager::CATEGORY_SYSTEM,
                'icon' => 'octo-icon-image',
                'class' => Settings::class,
                'order' => 600,
                'keywords' => 'image manipulation optimization watermark',
                'permissions' => ['renatio.image.access_settings'],
            ],
        ];
    }
}
