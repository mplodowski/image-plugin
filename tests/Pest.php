<?php

use Renatio\Image\Tests\TestCase;

uses(TestCase::class)
    ->beforeEach(fn () => $this->setUpOctoberPlugin())
    ->afterEach(fn () => $this->tearDownOctoberPlugin())
    ->in('Unit', 'Integration');
