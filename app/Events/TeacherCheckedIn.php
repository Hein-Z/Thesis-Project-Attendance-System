<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
use App\Models\Teacher;

class TeacherCheckedIn implements ShouldBroadcast
{
    // use InteractsWithSockets, SerializesModels;
 use  InteractsWithSockets, SerializesModels;

    public $attendance;

    public function __construct($attendance)
    {
        $this->attendance = $attendance;
    }

    public function broadcastOn()
    {
        return new Channel('teacher-attendance');
    }

    public function broadcastAs()
    {
        return 'teacher.checked-in';
    }

    public function broadcastWith()
    {
        return [
            'teacher_id' => $this->attendance->teacher_id,
            'time' => $this->attendance->check_in ?? $this->attendance->check_out,
        ];
    }
}
