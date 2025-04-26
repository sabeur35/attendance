<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassSession extends Model
{
    protected $fillable = [
        'course_id',
        'start_time',
        'end_time',
        'qr_code_secret',
        'latitude',
        'longitude',
        'radius_meters',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function generateQrCodeSecret()
    {
        $this->qr_code_secret = md5($this->id . time() . rand(1000, 9999));
        $this->save();

        return $this->qr_code_secret;
    }

    public function getQrCodeUrl()
    {
        return url('/attend/' . $this->qr_code_secret);
    }
}
