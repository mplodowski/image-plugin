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
        'queue_optimization' => ['boolean'],
        'optimization_timeout' => ['required', 'integer', 'min:1'],
        'strip_metadata' => ['boolean'],
        'optimizer_quality' => ['required', 'integer', 'between:0,100'],
        'binary_path' => ['nullable', 'string'],
        'watermark' => ['nullable', 'string'],
        'watermark_position' => ['required', 'string'],
        'watermark_alpha' => ['required', 'integer', 'between:0,100'],
        'watermark_padding_x' => ['required', 'integer'],
        'watermark_padding_y' => ['required', 'integer'],
        'watermark_padding_unit' => ['required', 'in:pixel,percent'],
        'watermark_width' => ['required', 'integer', 'min:0'],
        'watermark_height' => ['required', 'integer', 'min:0'],
        'watermark_size_unit' => ['required', 'in:pixel,percent'],
        'watermark_fit' => ['required', 'in:contain,max,fill,stretch,crop,fill-max'],
    ];

    public function initSettingsData(): void
    {
        foreach (config('renatio.image::manipulations', []) as $key => $value) {
            $this->{$key} = $value;
        }
    }
}
