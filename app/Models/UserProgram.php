<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProgram extends Model
{
    use HasFactory;

    // Supaya Laravel tahu kolom apa aja yang boleh kita isi
    protected $fillable = [
        'user_id',
        'program_id',
        'status',
        'completed_at'
    ];
}