<?php

namespace Tests\Unit;

use App\Models\Question;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class QuestionTest extends TestCase
{
    public function testFillable()
    {
        $user = new Question();
        $this->assertEquals(
            ['question', 'answer', 'done'],
            $user->getFillable()
        );
    }
}
