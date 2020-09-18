<?php
namespace App\Services;

use App\Repositories\UserRepository;
use Throwable;

class UserService
{
    private $repository;

    /**
     * UserService constructor.
     * @param    $repository
     */
    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }
}
