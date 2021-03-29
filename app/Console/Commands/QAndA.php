<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\{EventService, MenuService, UserService, QuestionSerivce};

class QAndA extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'qanda:interactive';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Runs an interactive command line based Q And A system.';

    private $userService, $questionService, $menuService, $eventService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->userService = app(UserService::class);
        $this->questionService = app(QuestionSerivce::class);
        $this->menuService = app(MenuService::class);
        $this->eventService = app(EventService::class);
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Create your interactive Q And A system here. Be sure to make use of all of Laravels functionalities.

        $this->info('');
        $this->info('Q/A Application!');
        $this->info('');

        try {
            $this->initialInteraction();
        } catch (\Exception $e) {
            $this->info($e->getMessage());
            return $e->getCode();
        }
    }

    public function initialInteraction()
    {
        $this->menuService->initialInteraction();
        $option = $this->ask('Enter the option number of your choice:');
        $this->chooseOptionInitialInteraction($option);
    }

    public function previouslyAnswers($validation = 0)
    {
        if ($validation == 0) {
            $this->line('Hello! Who are you?');
        } else {
            $this->line('Invalid Email! Try again.');
        }

        $emailUser = $this->ask('Please, enter your Email or 888 to Go Back or 999 to Exit');
        $this->chooseBackOrExit($emailUser);

        if(!$validate = $this->userService->validateEmail($emailUser))
            $this->previouslyAnswers(1);

        //Verify if it is a new user
        $user = $this->userService->selectUser($emailUser);

        if ($user) {
            $this->eventService->startPreviouslyAnswersEvent($user);
            $option = $this->ask('Enter the option number of your choice:');
            $this->chooseOption($option,$user);
        // New User
        } else {
            $this->info("You have not answered any questions yet.");
            if ($this->confirm('Would you like to start answering some questions now?'))
                $this->practicingQuestions();

            $this->initialInteraction();
        }
    }

    public function addNewQuestionAnswer()
    {
        $this->info("Let's Add a New Question");
        $dataQuestion['question'] = $this->ask('Enter the question or 888 to Go Back or 999 to Exit:.');
        $this->chooseBackOrExit($dataQuestion['question']);

        $dataQuestion['answer'] = $this->ask('Enter the answer or 888 to Go Back or 999 to Exit:');
        $this->chooseBackOrExit($dataQuestion['answer']);

        //Save Question and Answer
        $result = $this->questionService->store($dataQuestion);

        $this->initialInteraction();
    }

    public function practicingQuestions($validation = 0)
    {
        if ($validation == 0) {
            $this->line('Hello! Who are you?');
        } else {
            $this->line('Invalid Email! Try again.');
        }

        $emailUser = $this->ask('Please, enter your Email or 888 to Go Back or 999 to Exit');
        $this->chooseBackOrExit($emailUser);

        if(!$validate = $this->userService->validateEmail($emailUser))
            $this->practicingQuestions(1);

        //Verify if it is a new user
        $user = $this->userService->selectUser($emailUser);
        if ($user) {
            $this->line("Hello {$user->name}! Welcome back!");
            $this->showQuestions($user);
        }

        $nameUser = $this->ask('Please, enter your Name or 888 to Go Back or 999 to Exit');
        $this->chooseBackOrExit($nameUser);
        $dataUser['name'] = $nameUser;
        $dataUser['email'] = $emailUser;

        //Save new User
        $user = $this->userService->store($dataUser);

        $this->info("Hello {$user->name}! This is your first time here. Welcome!");
        $this->showQuestions($user);
    }

    public function showQuestions($user)
    {
        //Header of questions
        $this->info('List of the Questions');
        $headers = ['Number', 'Question'];

        // Select all questions and list them
        $questions = $this->questionService->selectFields();
        $this->table($headers, $questions);

        //Without Questions
        if (empty($questions)){
            $this->info('' );
            $this->info('There are No Questions yet' );
            $option = $this->ask('Enter 1 to Go Back or 999 to Exit');
            $this->chooseOptionNoQuestions($option, $user);
        }

        // Get ID of the selected question
        $questionId = $this->ask("Enter the question number you want to practice or 888 to Go Back or 999 to Exit:");
        $this->chooseBackOrExit($questionId);

        //Verify if User selected the correct option
        $questionsId = $this->questionService->arrayIdQuestions();
        if (!in_array($questionId, $questionsId)) {
            $this->info('Wrong option. Choose again:');
            $this->showQuestions($user);
        }

        //Get the answer to the selected question and prepare the multiple choice for the question
        $question = $this->questionService->findById($questionId);
        $answers = $this->questionService->getAnswerSelectedQuestion($question);

        //Show the question with multiple choice
        $this->info('Answer the question below:' );
        $userAnswer = $this->choice($question->question, $answers);

        //Save User's Answer
        $saved = $this->userService->saveAnswer($user->id, $question->id, $userAnswer);

        //Check If User Answered All the Questions
        $check = $this->userService->checkUserEndQuestions($user->id);
        if($check)
            $this->overviewFinalProgress($user->id);

        if($this->userService->checkAnswer($userAnswer, $question->id)) {
            $this->info('Correct Answer!! Congrats!');
            $this->listQuestionsProgress($user);
        }

        $this->info('Oo! Wrong Answer!');
        $this->listQuestionsProgress($user);
    }

    public function listQuestionsProgress($user)
    {
        $this->eventService->startQuestionsProgressEvent($user);
        $option = $this->ask('Enter the option number of your choice:');

        $this->chooseOption($option, $user);
    }

    public function overviewFinalProgress($userId)
    {
        $user = $this->userService->findById($userId);
        $this->eventService->startFinalOverviewEvent($user);

        $option = $this->ask('Enter the option number of your choice:');
        $this->chooseOption($option,$user);
    }

    public function chooseOptionInitialInteraction($option)
    {
        if ($option == 1)
            $this->addNewQuestionAnswer();

        if ($option == 2)
            $this->previouslyAnswers();

        if ($option == 999)
            $this->menuService->exit();

        if (!in_array($option, [1, 2, 999])) {
            $this->info('Wrong option. Choose again:');
            $this->initialInteraction();
        }
    }

    public function chooseBackOrExit($option)
    {
        if ($option == 888)
            $this->initialInteraction();

        if ($option == 999)
            $this->menuService->exit();
    }

    public function chooseOption($option, $user)
    {
        if ($option == 1)
            $this->showQuestions($user);

        if ($option == 2)
            $this->initialInteraction();

        if ($option == 999)
            $this->menuService->exit();

        if (!in_array($option, [1, 2, 999])) {
            $this->info('Wrong option. Choose again:');
            $this->listQuestionsProgress($user);
        }
    }

    public function chooseOptionNoQuestions($option, $user)
    {
        if ($option == 1)
            $this->initialInteraction();

        if ($option == 999)
            $this->menuService->exit();

        if (!in_array($option, [1, 999])) {
            $this->info('Wrong option. Choose again:');
            $this->showQuestions($user);
        }
    }
}