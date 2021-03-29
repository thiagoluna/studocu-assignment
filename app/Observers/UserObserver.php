<?php


namespace App\Observers;


use Illuminate\Database\Eloquent\Model;

class UserObserver
{
    public function creating(Model $model)
    {
        $model->setAttribute('password', bcrypt('secret'));
    }
}