<?php

namespace App\Policies;

use App\Models\Agendamento;
use App\Models\User;

class AgendamentoPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    public function view(User $user, Agendamento $agendamento): bool
    {
        return $user->isAdmin() || $agendamento->user_id === $user->getKey();
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, Agendamento $agendamento): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, Agendamento $agendamento): bool
    {
        return $user->isAdmin();
    }
}

