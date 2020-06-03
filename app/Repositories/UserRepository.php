<?php
namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserRepository
{
    protected $model;

    public function __construct(User $user)
    {
        $this->model = $user;
    }

    /**
     * @param $attributes
     */
    public function add($attributes)
    {
        $this->model->query()->create([
            'username' => $attributes['username'],
            'password' => Hash::make($attributes['password'])
        ]);
    }
}
