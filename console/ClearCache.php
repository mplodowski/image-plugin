<?php

namespace Renatio\Image\Console;

use Illuminate\Console\Command;
use Renatio\Image\Classes\ImageManipulation;

class ClearCache extends Command
{
    protected $signature = 'image:clear';

    public function __construct()
    {
        parent::__construct();

        $this->description = (string) trans('renatio.image::lang.console.clear_cache_description');
    }

    public function handle(ImageManipulation $manipulation): int
    {
        $removed = $manipulation->clearCache();

        $this->info(trans('renatio.image::lang.console.clear_cache_result', ['count' => $removed]));

        return self::SUCCESS;
    }
}
