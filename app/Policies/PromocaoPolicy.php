<?php

namespace App\Policies;

use App\Models\Promocao;
use App\Models\User;

class PromocaoPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    public function view(User $user, Promocao $promocao): bool
    {
        return $user->isAdmin();
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, Promocao $promocao): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, Promocao $promocao): bool
    {
        return $user->isAdmin();
    }
}
