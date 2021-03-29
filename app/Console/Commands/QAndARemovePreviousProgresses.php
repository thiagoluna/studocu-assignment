<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class QAndARemovePreviousProgresses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'qanda:reset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Removes all previous progresses';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        //Initializing UserService Layer
        $this->userService = app(\App\Services\UserService::class);
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $users = $this->userService->removePreviousProgresses();

        $this->info("-------------------------------------");
        $this->info('All Previous Progress for All Users has been removed.');
    }
}
