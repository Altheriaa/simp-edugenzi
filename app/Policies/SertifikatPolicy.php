<?php

namespace App\Policies;

use App\Models\Sertifikat;
use App\Models\User;

class SertifikatPolicy
{
    /** Hanya mentor yang menerbitkan sertifikat ini yang boleh update/delete */
    public function update(User $user, Sertifikat $sertifikat): bool
    {
        return $user->id === $sertifikat->mentor_id;
    }

    public function delete(User $user, Sertifikat $sertifikat): bool
    {
        return $user->id === $sertifikat->mentor_id;
    }
}
