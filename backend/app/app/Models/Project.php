<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\User;
use App\Models\TimeEntry;

class Project extends Model
{
            public function user()
        {
            return $this->belongsTo(User::class);
        }

        public function timeEntries()
        {
            return $this->hasMany(TimeEntry::class);
        }
}
