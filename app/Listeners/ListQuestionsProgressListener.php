<?php

namespace App\Listeners;

use App\Events\QuestionsProgressEvent;
use App\Facades\ConsoleOutput;
use App\Models\Question;
use App\Services\UserService;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ListQuestionsProgressListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        $this->userService = app(UserService::class);
    }

    /**
     * Handle the event.
     *
     * @param  QuestionsProgressEvent  $event
     * @return void
     */
    public function handle(QuestionsProgressEvent $event)
    {
        $user = $event->getUser();

        //Reload current User
        $user = $this->userService->findById($user->id);

        ConsoleOutput::writeln('--');
        ConsoleOutput::writeln('<info>list of all questions, and your progress for each question.</info>');

        $questions = Question::all();
        foreach ($questions as $question) {
            $done = 'Not Done';

            //Find all questions answered by the user
            foreach ($user->questions as $user_question) {
                if ($user_question->pivot->question_id == $question->id) {
                    $done = 'Done';
                }
            }
            ConsoleOutput::writeln('Question ' . $question->id . ' - ' . $done);
        }

        ConsoleOutput::writeln('--');
        ConsoleOutput::writeln('<info>Choose one option below:</info>');
        ConsoleOutput::writeln('1- Answer Another Questions');
        ConsoleOutput::writeln('2- Home');
        ConsoleOutput::writeln('999- Exit');
    }
}
