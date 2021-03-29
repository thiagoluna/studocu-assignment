<?php


namespace App\Repositories;

use App\Models\Question;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Database\QueryException;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    public function model()
    {
        return User::class;
    }

    public function saveAnswer($userId, $questionId, $userAnswer): bool
    {
        $user = $this->findById($userId);
        if(!$user)
            return false;

        $status = 'Wrong Answer';
        $checkAnswer = $this->checkAnswer($userAnswer, $questionId);

        if($checkAnswer)
            $status = 'Correct Answer';

        try {
            //Clear old answer
            $user->questions()->detach($questionId);

            //Save answer
            $user->questions()->attach($userId, ['question_id' => $questionId, 'user_answer' => $userAnswer, 'status' => $status]);
            return true;
        } catch (QueryException $e) {
            return false;
        }
    }

    public function checkAnswer($userAnswer, $questionId): bool
    {
        //Select the correct answer from tue question
        $correctAnswer = Question::find($questionId);

        if($correctAnswer) {
            if ($userAnswer == $correctAnswer->answer)
                return true;
        }

        return false;
    }

    public function checkUserEndQuestions($userId): bool
    {
        $total_questions = Question::all()->count();

        $user = $this->findById($userId);
        //$total_User_asnwerrs = $user->questions()->wherePivot('user_id', $userId)->count();
        $total_User_asnwerrs = $user->questions()->count();

        if ($total_questions == $total_User_asnwerrs)
            return true;

        return false;
    }

    public function countRights($user)
    {
        return $user->questions()->wherePivot('status', 'Correct Answer')->count();
    }

    public function countMistakes($user)
    {
        return $user->questions()->wherePivot('status', 'Wrong Answer')->count();

    }

    public function removePreviousProgresses()
    {
        $users = $this->getAll();

        foreach ($users as $user) {
            $user->questions()->detach();
        }
    }
}