<?php

namespace App\Observers;

use App\Models\User;

class UserObserver
{
    private function clearCache(){
        if(cache()->get('users-all-last-page')===null) return;
        $user_last_page = cache()->get('users-all-last-page');
        for($i = 1; $i <= $user_last_page; $i++){
            $key = 'users-all:page-'. $i;
            if(cache()->has($key)){
                cache()->forget($key);
            }
        }
        cache()->forget('users-all-last-page');
    }
    
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        cache()->forget('users-info-id:'.$user->getAttribute('id'));
        $this->clearCache();
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        cache()->forget('users-info-id:'.$user->getAttribute('id'));
        $this->clearCache();
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        cache()->forget('users-info-id:'.$user->getAttribute('id'));
        $this->clearCache();
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }
}
