<?php

use Renatio\Image\Tests\TestCase;

uses(TestCase::class)
    ->beforeEach(function () {
        $this->setUpOctoberPlugin();
    })
    ->afterEach(function () {
        $this->tearDownOctoberPlugin();
    })
    ->in(__DIR__);
