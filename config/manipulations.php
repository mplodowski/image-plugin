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

];
