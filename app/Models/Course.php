<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model
{
    protected $fillable = [
        'kode', 'nama', 'sks', 'dosen', 'hari',
        'jam_mulai', 'jam_selesai', 'ruangan',
    ];

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }
}