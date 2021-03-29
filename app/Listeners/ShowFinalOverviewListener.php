<?php

namespace App\Listeners;

use App\Events\FinalOverviewEvent;
use App\Facades\ConsoleOutput;
use App\Services\QuestionSerivce;
use App\Services\UserService;
use Illuminate\Console\Command;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;


class ShowFinalOverviewListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        $this->userService = app(UserService::class);
        $this->questionService = app(QuestionSerivce::class);
    }

    /**
     * Handle the event.
     *
     * @param  FinalOverviewEvent  $event
     * @return void
     */
    public function handle(FinalOverviewEvent $event)
    {
        $user = $event->getUser();

        $countRights = $this->userService->countRights($user);
        $countMistakes = $this->userService->countMistakes($user);

        ConsoleOutput::writeln('---------------------------------------');
        ConsoleOutput::writeln('<info>CONGRATULATIONS!!!</info>');
        ConsoleOutput::writeln('You Answered All the Questions!!! \o/');
        ConsoleOutput::writeln('---------------------------------------');

        $this->userService->getQuestionsAnsweredByUser($user);

        ConsoleOutput::writeln('');
        ConsoleOutput::writeln('<info>Your Results</info>');
        ConsoleOutput::writeln('---------------------------------------');
        ConsoleOutput::writeln("- You got {$countRights} questions right." );
        ConsoleOutput::writeln("- You missed {$countMistakes} questions.");
        ConsoleOutput::writeln('---------------------------------------');

        ConsoleOutput::writeln('');
        ConsoleOutput::writeln('<info>What would you like to do now?</info>');
        ConsoleOutput::writeln('1- View Questions Again');
        ConsoleOutput::writeln('2- Home');
        ConsoleOutput::writeln('999- Exit');
    }
}
