<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class RecordAttendanceOnLogin
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  Login  $event
     * @return void
     */
    public function handle(Login $event)
    {
        $user = $event->user;
        
        if ($user->level == 2) { // Kasir
            $today = date('Y-m-d');
            $existingAttendance = \App\Models\Attendance::where('user_id', $user->id)
                                    ->where('date', $today)
                                    ->first();
                                    
            if (!$existingAttendance) {
                $hour = (int) date('H');
                
                if ($hour >= 6 && $hour < 12) {
                    $shift = 'Pagi';
                } elseif ($hour >= 12 && $hour < 18) {
                    $shift = 'Siang';
                } elseif ($hour >= 18 && $hour < 22) {
                    $shift = 'Sore';
                } else {
                    $shift = 'Malam';
                }
                
                \App\Models\Attendance::create([
                    'user_id' => $user->id,
                    'date' => $today,
                    'shift' => $shift,
                    'clock_in' => date('H:i:s'),
                ]);
            }
        }
    }
}
