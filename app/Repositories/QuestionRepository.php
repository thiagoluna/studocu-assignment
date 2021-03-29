<?php


namespace App\Repositories;


use App\Models\Question;
use App\Repositories\Contracts\QuestionRepositoryInterface;

class QuestionRepository extends BaseRepository implements QuestionRepositoryInterface
{
    public function model()
    {
        return Question::class;
    }
}