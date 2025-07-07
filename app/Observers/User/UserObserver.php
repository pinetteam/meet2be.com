<?php

namespace App\Observers\User;

use App\Models\User\User;

class UserObserver
{
    /**
     * Handle the User "creating" event.
     */
    public function creating(User $user): void
    {
        // Set default values if not set
        if (empty($user->status)) {
            $user->status = User::STATUS_ACTIVE;
        }
        
        if (empty($user->type)) {
            $user->type = User::TYPE_OPERATOR;
        }
    }
} 