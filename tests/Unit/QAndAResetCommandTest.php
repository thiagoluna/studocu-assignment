<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class QAndAResetCommandTest extends TestCase
{
    use DatabaseMigrations;

    public function testResetPreviousProgresses()
    {
        $this->artisan('qanda:reset')
            ->expectsOutput('-------------------------------------')
            ->expectsOutput('All Previous Progress for All Users has been removed.');
    }
}
