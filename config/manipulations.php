<?php

return [

    // Directory where manipulated images are cached. Relative to the project base path.
    'directory' => 'storage/app/uploads/public/manipulations/',

    // Default watermark image path used by the `watermark` filter when no image is passed.
    'watermark' => themes_path('demo/assets/images/watermark.png'),

    // Run the optimizer chain on every manipulation result.
    'optimize_after_manipulation' => true,

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
