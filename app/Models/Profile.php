<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    // Kolom apa saja yang boleh diisi
    protected $fillable = [
        'user_id',
        'full_name',
        'age',
        'height',
        'weight',
        'gender',
        'fitness_level',
        'goal',
        'profile_completed',
    ];

    // Relasi balik ke model User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isCompleteFor(User $user): bool
    {
        return ! empty($this->full_name)
            && ! empty($user->email)
            && ! empty($this->gender)
            && ! empty($this->age)
            && ! empty($this->height)
            && ! empty($this->weight);
    }
}