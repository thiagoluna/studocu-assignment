<?php


namespace App\Services;


use App\Facades\ConsoleOutput;
use App\Models\Question;
use App\Repositories\Contracts\QuestionRepositoryInterface;

class QuestionSerivce
{
    private $questionRepository;

    public function __construct(QuestionRepositoryInterface $questionRepository)
    {
        $this->questionRepository = $questionRepository;
    }

    public function findById($questionId)
    {
        return $this->questionRepository->findById($questionId);
    }

    public function getAll()
    {
        return $this->questionRepository->getAll();
    }

    public function selectFields(): Array
    {
        return $this->questionRepository->select('id', 'question')->toArray();
    }

    public function getAnswerSelectedQuestion($question): array
    {
        $answers = Question::select(['answer'])->where('answer','!=',$question->answer)->inRandomOrder()->get()->toArray();
        $arrayAnswer = [];
        //Each question should have 3 multiple choices
        if (empty($answers)) {
            array_push($arrayAnswer, ['answer' => $question->answer]);
            array_push($arrayAnswer, ['answer' => 'Yes']);
            array_push($arrayAnswer, ['answer' => 'No']);
        }

        if (count($answers) == 1) {
            array_push($arrayAnswer, ['answer' => $question->answer]);
            array_push($arrayAnswer, ['answer' => 'Amsterdam']);
            array_push($arrayAnswer, ['answer' => $answers[0]['answer']]);
        }

        if (count($answers) >= 2) {
            array_push($arrayAnswer, ['answer' => $question->answer]);
            array_push($arrayAnswer, ['answer' => $answers[0]['answer']]);
            array_push($arrayAnswer, ['answer' => $answers[1]['answer']]);
        }

        //Random the answers
        shuffle($arrayAnswer);
        foreach ($arrayAnswer as $answer){
            $randomArray[] = $answer['answer'];
        }

        return $randomArray;
    }

    public function store(array $dataQuestion): bool
    {
        $result = $this->questionRepository->store($dataQuestion);

        if ($result) {
            ConsoleOutput::writeln('<info>Question and Answer were Saved!</info>');
            ConsoleOutput::writeln('');
            return true;
        } else {
            ConsoleOutput::writeln('An error has occurred. Please, try again');
            return false;
        }
    }

    public function arrayIdQuestions()
    {
        $ids = $this->questionRepository->select('id');

        foreach ($ids as $id) {
            $arrayIds[] = $id->id;
        }

        return $arrayIds;
    }
}