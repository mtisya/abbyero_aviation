<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Verified;
use App\Models\User;
use App\Events\UserApproved;
use App\Notifications\NewUserRegisteredNotification;

class AutoApproveUserAfterVerification
{
    public function handle(Verified $event): void
    {
        $user = $event->user;

        if (!$user) return;

        // ✅ Approve user
        $user->status = 'approved';
        $user->save();

        // 🔥 FIRE EVENT (this is what you asked for)
        event(new UserApproved($user));

        // 🔔 Notify all admins
        $admins = User::where('role', 'admin')->get();

        foreach ($admins as $admin) {
            $admin->notify(new NewUserRegisteredNotification($user));
        }
    }
}