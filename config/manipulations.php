<?php

return [

    // Directory where manipulated images are cached. Relative to the project base path.
    'directory' => 'storage/app/uploads/public/manipulations/',

    // Default watermark image path used by the `watermark` filter when no image is passed.
    'watermark' => themes_path('demo/assets/images/watermark.png'),

    // Default watermark position. See Spatie\Image\Enums\AlignPosition for all allowed values.
    'watermark_position' => 'bottom-right',

    // Watermark opacity (0-100).
    'watermark_alpha' => 100,

    // Watermark padding from the chosen position.
    'watermark_padding_x' => 10,
    'watermark_padding_y' => 10,

    // Unit for padding values: `pixel` or `percent` (percent is relative to the base image size).
    'watermark_padding_unit' => 'pixel',

    // Watermark target size. 0 keeps the watermark at its native size.
    'watermark_width' => 0,
    'watermark_height' => 0,

    // Unit for watermark width/height: `pixel` or `percent`.
    'watermark_size_unit' => 'pixel',

    // Fit strategy when both width and height are set. See Spatie\Image\Enums\Fit.
    'watermark_fit' => 'contain',

    // Run the optimizer chain on every manipulation result.
    'optimize_after_manipulation' => true,

    // Dispatch the optimizer chain to a queue worker instead of running it during the request.
    // Requires a functioning queue worker; the initial render will return an unoptimized file.
    'queue_optimization' => false,

    // Seconds the optimizer chain is allowed to run before aborting.
    'optimization_timeout' => 120,

    // Strip EXIF, IPTC, ICC and XMP metadata during JPEG optimization.
    'strip_metadata' => true,

    // Quality target (0-100) passed to jpegoptim, pngquant, cwebp and avifenc during optimization.
    'optimizer_quality' => 80,

    // Path to the directory containing optimizer binaries (jpegoptim, cwebp, optipng, pngquant).
    // Set to null to rely on the system PATH. Commonly `/opt/homebrew/bin` on Apple Silicon.
    'binary_path' => env('IMAGE_OPTIMIZER_PATH', '/opt/homebrew/bin'),

];
