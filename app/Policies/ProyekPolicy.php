<?php

namespace App\Policies;

use App\Models\Proyek;
use App\Models\User;

class ProyekPolicy
{
    public function view(User $user, Proyek $proyek): bool
    {
        return $user->id === $proyek->user_id;
    }

    public function update(User $user, Proyek $proyek): bool
    {
        return $user->id === $proyek->user_id;
    }

    public function delete(User $user, Proyek $proyek): bool
    {
        return $user->id === $proyek->user_id;
    }
}
