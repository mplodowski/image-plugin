<?php

namespace Renatio\Image\Models;

use October\Rain\Database\Model;
use October\Rain\Database\Traits\Validation;

class Settings extends Model
{
    use Validation;

    public $implement = ['System.Behaviors.SettingsModel'];

    public $settingsCode = 'renatio_image_settings';

    public $settingsFields = 'fields.yaml';

    public $rules = [
        'directory' => ['required', 'string'],
        'optimize_after_manipulation' => ['boolean'],
        'optimization_timeout' => ['required', 'integer', 'min:1'],
        'strip_metadata' => ['boolean'],
        'optimizer_quality' => ['required', 'integer', 'between:0,100'],
        'binary_path' => ['nullable', 'string'],
        'watermark' => ['nullable', 'string'],
    ];

    public function initSettingsData(): void
    {
        $this->directory = 'storage/app/uploads/public/manipulations/';
        $this->watermark = themes_path('demo/assets/images/watermark.png');
        $this->optimize_after_manipulation = true;
        $this->optimization_timeout = 120;
        $this->strip_metadata = true;
        $this->optimizer_quality = 80;
        $this->binary_path = env('IMAGE_OPTIMIZER_PATH', '/opt/homebrew/bin');
    }
}
