<?php


namespace App\Services;


use App\Facades\ConsoleOutput;
use App\Models\Question;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Support\Facades\Validator;

class UserService
{
    private $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function findById($userId)
    {
        return $this->userRepository->findById($userId);
    }

    public function removePreviousProgresses()
    {
        return $this->userRepository->removePreviousProgresses();
    }

    public function selectUser(string $emailUser)
    {
        return $this->userRepository->findWhereFirst('email', $emailUser);
    }

    public function store(array $dataUser): User
    {
        return $this->userRepository->store($dataUser);
    }

    public function saveAnswer($userId, $questionId, $userAnswer): bool
    {
        return $this->userRepository->saveAnswer($userId, $questionId, $userAnswer);
    }

    public function checkAnswer($userAnswer, $questionId)
    {
        $correctAnswer = $this->userRepository->checkAnswer($userAnswer, $questionId);
        if ($correctAnswer)
            return true;

        return false;
    }

    public function checkUserEndQuestions($userId): bool
    {
        return $this->userRepository->checkUserEndQuestions($userId);
    }

    public function countRights($user)
    {
        return $this->userRepository->countRights($user);
    }

    public function countMistakes($user)
    {
        return $this->userRepository->countMistakes($user);

    }

    public function validateEmail($emailUser): bool
    {
        $validator = Validator::make(
            ['email' => $emailUser],
            ['email' => 'email']
        );

        if ($validator->fails())
            return false;

        return true;
    }

    public function getQuestionsAnsweredByUser($user)
    {
        //Reload Current User
        $user = $this->userRepository->findById($user->id);

        $questions = Question::all();
        foreach ($questions as $question) {
            $user_answer = 'No response';
            $user_answer_status = '';

            //Find all questions answered by the user
            foreach ($user->questions as $user_question) {
                if ($user_question->pivot->question_id == $question->id) {
                    $user_answer = $user_question->pivot->user_answer;
                    $user_answer_status = ' -> ' . $user_question->pivot->status;
                }
            }
            ConsoleOutput::writeln('Question ' . $question->id . ': ' . $question->question . ' -> '. $user_answer . $user_answer_status);
        }
    }
}