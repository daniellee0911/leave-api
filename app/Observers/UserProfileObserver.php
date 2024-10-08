<?php

namespace App\Observers;

use App\Models\UserProfile;

class UserProfileObserver
{
    /**
     * Handle the UserProfile "created" event.
     */
    public function created(UserProfile $userProfile): void
    {
        cache()->forget('users-info-id:'.$userProfile->getAttribute('user_id'));
    }

    /**
     * Handle the UserProfile "updated" event.
     */
    public function updated(UserProfile $userProfile): void
    {
        cache()->forget('users-info-id:'.$userProfile->getAttribute('user_id'));
    }

    /**
     * Handle the UserProfile "deleted" event.
     */
    public function deleted(UserProfile $userProfile): void
    {
        cache()->forget('users-info-id:'.$userProfile->getAttribute('user_id'));
    }

    /**
     * Handle the UserProfile "restored" event.
     */
    public function restored(UserProfile $userProfile): void
    {
        //
    }

    /**
     * Handle the UserProfile "force deleted" event.
     */
    public function forceDeleted(UserProfile $userProfile): void
    {
        //
    }
}

