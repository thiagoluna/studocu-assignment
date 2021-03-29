<?php

namespace App\Listeners;

use App\Events\PreviouslyAnswersEvent;
use App\Facades\ConsoleOutput;
use App\Services\UserService;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ListPreviouslyAnswersListener
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
     * @param  PreviouslyAnswersEvent  $event
     * @return void
     */
    public function handle(PreviouslyAnswersEvent $event)
    {
        $user = $event->getUser();

        if ($user) {
            ConsoleOutput::writeln('---------------------------------------');
            ConsoleOutput::writeln('<info>Previously Entered Answers</info>');
            ConsoleOutput::writeln('---------------------------------------');
            ConsoleOutput::writeln("Hello {$user->name}!");
            ConsoleOutput::writeln("See below your previously entered answers:");

            //Show all questions answered by the user
            $this->userService->getQuestionsAnsweredByUser($user);

            ConsoleOutput::writeln('--');
            ConsoleOutput::writeln('<info>Choose one option below:</info>');
            ConsoleOutput::writeln('1- View Questions');
            ConsoleOutput::writeln('2- Go Back');
            ConsoleOutput::writeln('999- Exit');
        }
    }
}
