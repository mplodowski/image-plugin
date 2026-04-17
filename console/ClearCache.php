<?php

namespace Renatio\Image\Console;

use Illuminate\Console\Command;
use Renatio\Image\Classes\ImageManipulation;

class ClearCache extends Command
{
    protected $signature = 'image:clear';

    protected $description = 'Remove all cached manipulated images.';

    public function handle(ImageManipulation $manipulation): int
    {
        $removed = $manipulation->clearCache();

        $this->info("Removed $removed cached image(s).");

        return self::SUCCESS;
    }
}
