<?php


namespace App\Services;


use App\Facades\ConsoleOutput;

class MenuService
{

    public function initialInteraction()
    {
         ConsoleOutput::writeln('---------------------------------------');
         ConsoleOutput::writeln('<info>HOME</info>');
         ConsoleOutput::writeln('---------------------------------------');
         ConsoleOutput::writeln('<info>Choose one option below:</info>');
         ConsoleOutput::writeln('1- Add Questions and Answers');
         ConsoleOutput::writeln('2- View Previously Entered Answers');
         ConsoleOutput::writeln('999- Exit');
    }

    public function exit()
    {
        throw new \Exception('By for now!', 0);
    }


}