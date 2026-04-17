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
        foreach (config('renatio.image::manipulations', []) as $key => $value) {
            $this->{$key} = $value;
        }
    }
}
