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

    public function getUserList()
    {
        return $this->repository->simplePaginate();
    }

    public function getUserById($id)
    {
        return $this->repository->find($id);
    }

    /**
     * @param $data
     */
    public function register($data)
    {
        $this->repository->add($data);
    }
}
