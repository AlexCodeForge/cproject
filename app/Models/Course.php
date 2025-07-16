<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'image_url',
        'is_premium',
        'status',
    ];

    public function sections()
    {
        return $this->hasMany(CourseSection::class);
    }

    public function lessons()
    {
        return $this->hasManyThrough(Lesson::class, CourseSection::class);
    }

    public function enrollments()
    {
        return $this->belongsToMany(User::class, 'course_enrollments');
    }
}
