<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_section_id',
        'title',
        'slug',
        'video_path',
        'content',
        'order',
    ];

    public function courseSection()
    {
        return $this->belongsTo(CourseSection::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'lesson_user')
            ->withTimestamps()
            ->withPivot('completed_at');
    }
}
