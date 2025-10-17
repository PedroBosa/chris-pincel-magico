<?php

namespace App\Policies;

use App\Models\Servico;
use App\Models\User;

class ServicoPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    public function view(User $user, Servico $servico): bool
    {
        return $user->isAdmin();
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, Servico $servico): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, Servico $servico): bool
    {
        return $user->isAdmin();
    }
}

