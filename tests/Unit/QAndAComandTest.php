<?php

namespace Tests\Unit;

use App\Models\Question;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class QAndAComandTest extends TestCase
{
    use DatabaseMigrations,DatabaseTransactions;

    public function testIfHasQandACommand()
    {
        $this->assertTrue(class_exists(\App\Console\Commands\QAndA::class));
    }

    public function testHandle()
    {
        $this->artisan('qanda:interactive')
            ->expectsOutput('')
            ->expectsOutput('Q/A Application!')
            ->expectsOutput('');
    }

    public function testinitialInteractionWithOption999()
    {
        $this->artisan('qanda:interactive')
            ->expectsOutput('')
            ->expectsQuestion('Enter the option number of your choice:', 999)
            ->expectsOutput('By for now!')
            ->assertExitCode(0);
    }

    public function testinitialInteractionWithOption1()
    {
        $this->artisan('qanda:interactive')
            ->expectsOutput('')
            ->expectsQuestion('Enter the option number of your choice:', 1)
            ->expectsQuestion('Enter the question or 888 to Go Back or 999 to Exit:.', '2 + 2')
            ->expectsQuestion('Enter the answer or 888 to Go Back or 999 to Exit:', '4');
    }

    public function testExit()
    {
        $this->artisan('qanda:interactive')
            ->expectsOutput('')
            ->expectsQuestion('Enter the option number of your choice:', 1)
            ->expectsQuestion('Enter the question or 888 to Go Back or 999 to Exit:.', 999)
            ->assertExitCode(0);
    }

    public function testinitialInteractionWithOption2()
    {
        $this->artisan('qanda:interactive')
            ->expectsOutput('')
            ->expectsQuestion('Enter the option number of your choice:', 2)
            ->expectsOutput('Hello! Who are you?')
            ->expectsQuestion('Please, enter your Email or 888 to Go Back or 999 to Exit', 'foo@email.com')
            ->expectsOutput('You have not answered any questions yet.');
    }

    public function testinitialInteractionWithOption2NoStartAnswer()
    {
        $this->artisan('qanda:interactive')
            ->expectsOutput('')
            ->expectsQuestion('Enter the option number of your choice:', 2)
            ->expectsOutput('Hello! Who are you?')
            ->expectsQuestion('Please, enter your Email or 888 to Go Back or 999 to Exit', 'foo@email.com')
            ->expectsOutput('You have not answered any questions yet.')
            ->expectsQuestion('Would you like to start answering some questions now?', 'no');
    }

    public function testinitialInteractionWithOption2StartAnswer()
    {
        $this->artisan('qanda:interactive')
            ->expectsOutput('')
            ->expectsQuestion('Enter the option number of your choice:', 2)
            ->expectsOutput('Hello! Who are you?')
            ->expectsQuestion('Please, enter your Email or 888 to Go Back or 999 to Exit', 'foo@email.com')
            ->expectsOutput('You have not answered any questions yet.')
            ->expectsQuestion('Would you like to start answering some questions now?', 'yes')
            ->expectsOutput('Hello! Who are you?')
            ->expectsQuestion('Please, enter your Email or 888 to Go Back or 999 to Exit', 'foo@email.com')
            ->expectsQuestion('Please, enter your Name or 888 to Go Back or 999 to Exit', 'My Name')
            ->expectsOutput('Hello My Name! This is your first time here. Welcome!')
            ->expectsOutput('List of the Questions');
    }

    public function testAnsweringOneQuestion()
    {
        $user = $this->createUser();
        $question = $this->createQuestion();

        $this->artisan('qanda:interactive')
            ->expectsOutput('')
            ->expectsQuestion('Enter the option number of your choice:', 2)
            ->expectsOutput('Hello! Who are you?')->expectsQuestion('Please, enter your Email or 888 to Go Back or 999 to Exit', $user->email)
            ->expectsQuestion('Enter the option number of your choice:', 1)
            ->expectsQuestion('Enter the question number you want to practice or 888 to Go Back or 999 to Exit:', 1)
            ->expectsOutput('Answer the question below:')
            ->expectsQuestion('How much is 2 + 2', 4);
    }

    public function createUser()
    {
        $data = [
            'name' => 'User Name',
            'email' => 'email@test.com',
            'password' => bcrypt('secret'),
        ];
        return User::create($data);
    }

    public function createQuestion()
    {
        $data = [
            'question' => 'How much is 2 + 2',
            'answer' => '4',
        ];
        return $question = Question::create($data);
    }
}
