<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Project;
use App\Models\User;

class TimeEntry extends Model
{

       protected $guarded = [];

       protected $casts = [
            'start_time' => 'datetime',
            'end_time' => 'datetime',
            ];

        public function project()
        {
            return $this->belongsTo(Project::class);
        }

        public function user()
        {
            return $this->belongsTo(User::class);
        }
}
