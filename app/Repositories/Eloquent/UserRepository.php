<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class UserRepository extends BaseRepository
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function getAll(): Collection
    {
        return $this->all(['*'], []);
    }

    public function getAllUsers($admin = false): Collection
    {
        return $this->all(['*'], [])->where('is_admin', $admin);
    }
}
