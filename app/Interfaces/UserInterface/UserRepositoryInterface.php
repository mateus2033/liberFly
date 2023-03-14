<?php

namespace App\Interfaces\UserInterface;

use App\Models\User;

interface UserRepositoryInterface
{
    public function getAll();
    public function findById(int $id);
    public function create(array $data);
    public function update(User $address, array $data);
    public function destroy(int $id);
}
