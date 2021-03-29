<?php

use Illuminate\Database\Seeder;
use App\Models\Question;


class QuentionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $question = Question::all()->count();
        if($question == 0) {
            Question::create(
                [
                    "question" => "The result of (9 + 2) - (1 + 6) is ",
                    "answer" => "4"
                ]
            );

            Question::create(
                [
                    "question" => "The result of (9 + (2 - 1)) + 6 is ",
                    "answer" => "16"
                ]
            );

            Question::create(
                [
                    "question" => "The result of (12 + 9 + 5) - (4 + 1) is ",
                    "answer" => "21"
                ]
            );
        }
    }
}
