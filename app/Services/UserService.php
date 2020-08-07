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

    /**
     * @param $id
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     */
    public function getUserById($id)
    {
        return $this->repository->find($id);
    }

    /**
     * 返回已有或需要创建的用户id
     *
     * @param $data
     * @return int
     */
    public function check($data) : int
    {
        if ($user = $this->repository->find($data['code'])) {
            return $user['id'];
        }
        $user = $this->repository->add($data);
        return $user['id'];

    }
}
