<?php


namespace App\Services;


use App\Events\FinalOverviewEvent;
use App\Events\PreviouslyAnswersEvent;
use App\Events\QuestionsProgressEvent;

class EventService
{
    public function startQuestionsProgressEvent($user)
    {
        event(new QuestionsProgressEvent($user));
    }

    public function startPreviouslyAnswersEvent($user)
    {
        event(new PreviouslyAnswersEvent($user));
    }

    public function startFinalOverviewEvent($user)
    {
        event(new FinalOverviewEvent($user));
    }
}