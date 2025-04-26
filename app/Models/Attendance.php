<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = [
        'user_id',
        'class_session_id',
        'check_in_time',
        'mac_address',
        'latitude',
        'longitude',
        'is_verified',
        'verification_message',
    ];

    protected $casts = [
        'check_in_time' => 'datetime',
        'is_verified' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function classSession()
    {
        return $this->belongsTo(ClassSession::class);
    }

    public function verifyAttendance()
    {
        $valid = true;
        $messages = [];

        // Check if the student is registered for this course
        $student = $this->user;
        $course = $this->classSession->course;

        if (!$student->courses->contains($course->id)) {
            $valid = false;
            $messages[] = 'Student not registered for this course.';
        }

        // Check if MAC address is registered for the student
        $registeredDevice = UserDevice::where('user_id', $this->user_id)
            ->where('mac_address', $this->mac_address)
            ->where('is_verified', true)
            ->first();

        if (!$registeredDevice) {
            $valid = false;
            $messages[] = 'Unregistered or unverified device used.';
        }

        // Check location proximity
        $session = $this->classSession;
        $distance = $this->calculateDistance(
            $session->latitude,
            $session->longitude,
            $this->latitude,
            $this->longitude
        );

        if ($distance > $session->radius_meters) {
            $valid = false;
            $messages[] = 'Location verification failed. Too far from class.';
        }

        // Check time
        $now = now();
        if ($now->lt($session->start_time) || $now->gt($session->end_time->addMinutes(15))) {
            $valid = false;
            $messages[] = 'Time verification failed. Not within class session time.';
        }

        $this->is_verified = $valid;
        $this->verification_message = implode(' ', $messages);
        $this->save();

        return $valid;
    }

    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        // Haversine formula to calculate distance between two points
        $earthRadius = 6371000; // in meters

        $latFrom = deg2rad($lat1);
        $lonFrom = deg2rad($lon1);
        $latTo = deg2rad($lat2);
        $lonTo = deg2rad($lon2);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) + cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));

        return $angle * $earthRadius;
    }
}
