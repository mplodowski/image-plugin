<?php

return [
    'plugin' => [
        'name' => 'Image',
        'description' => 'Manipulate images with Twig filters.',
    ],
    'permissions' => [
        'tab' => 'Image',
        'settings' => 'Manage image plugin settings',
    ],
    'settings' => [
        'label' => 'Image',
        'description' => 'Configure image manipulation and optimization.',
        'field' => [
            'directory' => 'Manipulations directory',
            'directory_comment' => 'Path (relative to the project base) where manipulated images are cached.',
            'watermark' => 'Default watermark image',
            'watermark_comment' => 'Absolute path to the image used by the watermark filter when no image is passed.',
            'optimize_after_manipulation' => 'Optimize after manipulation',
            'optimize_after_manipulation_comment' => 'Run the optimizer chain on every manipulation result.',
            'optimization_timeout' => 'Optimization timeout (seconds)',
            'optimization_timeout_comment' => 'Maximum time the optimizer chain is allowed to run before aborting.',
            'strip_metadata' => 'Strip metadata',
            'strip_metadata_comment' => 'Remove EXIF, IPTC, ICC and XMP metadata from JPEGs during optimization.',
            'queue_optimization' => 'Queue optimization',
            'queue_optimization_comment' => 'Push the optimizer chain to a queue worker instead of running it during the request. The initial render returns an unoptimized file and a worker replaces it in the background. Requires a running queue worker.',
            'optimizer_quality' => 'Optimizer quality (0-100)',
            'optimizer_quality_comment' => 'Quality target passed to jpegoptim, pngquant, cwebp and avifenc.',
            'binary_path' => 'Binary path',
            'binary_path_comment' => 'Directory containing optimizer binaries (jpegoptim, cwebp, optipng, pngquant). Leave empty to rely on the system PATH.',
        ],
    ],
];
