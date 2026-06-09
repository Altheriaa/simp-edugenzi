<?php

namespace App\Policies;

use App\Models\Penilaian;
use App\Models\User;

class PenilaianPolicy
{
    /** Hanya mentor yang membuat penilaian ini yang boleh update/delete */
    public function update(User $user, Penilaian $penilaian): bool
    {
        return $user->id === $penilaian->mentor_id;
    }

    public function delete(User $user, Penilaian $penilaian): bool
    {
        return $user->id === $penilaian->mentor_id;
    }
}
