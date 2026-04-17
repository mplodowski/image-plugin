<?php

namespace Renatio\Image\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Renatio\Image\Classes\ImageManipulation;

class OptimizeImageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 2;

    public function __construct(public string $imagePath) {}

    public function handle(ImageManipulation $manipulation): void
    {
        if (! file_exists($this->imagePath)) {
            Log::warning('Queued optimization skipped: file missing', ['path' => $this->imagePath]);

            return;
        }

        $manipulation->runOptimizer($this->imagePath);
    }
}
