<?php

namespace Tests\Unit;

use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    public function testFillable()
    {
        $user = new User();
        $this->assertEquals(
            ['name','email','password'],
            $user->getFillable()
        );
    }
}
