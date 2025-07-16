<?php

namespace App\Livewire\UserPanel;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

#[Layout('layouts.app')]
class ShowCourse extends Component
{
    public Course $course;
    public ?Lesson $currentLesson = null;
    public $sections = [];
    public $progress = [];
    public $hasPremiumAccess = false;

    public function mount(Course $course)
    {
        $this->course = $course;

        // Eager load sections and lessons with their order
        $this->course->load(['sections' => function ($query) {
            $query->orderBy('order')->with(['lessons' => function ($query) {
                $query->orderBy('order');
            }]);
        }]);

        $this->sections = $this->course->sections->toArray();

        $user = Auth::user();
        if ($user) {
            $this->hasPremiumAccess = $user->isPremium();
            $this->loadProgress($user);
        }

        // Set initial lesson if available, prioritize first lesson of first section
        if (!empty($this->sections) && !empty($this->sections[0]['lessons'])) {
            $this->currentLesson = Lesson::find($this->sections[0]['lessons'][0]['id']);
        }
    }

    public function loadProgress($user)
    {
        $completedLessons = $user->completedLessons()->pluck('lesson_id')->toArray();
        $this->progress = array_fill_keys($completedLessons, true);
    }

    public function selectLesson(Lesson $lesson)
    {
        // Check premium access for premium courses
        if ($this->course->is_premium && !$this->hasPremiumAccess) {
            session()->flash('message', 'Debes tener una suscripción premium para acceder a esta lección.');
            return redirect()->route('premium'); // Redirect to premium page
        }
        $this->currentLesson = $lesson;
    }

    public function markLessonAsComplete(Lesson $lesson)
    {
        $user = Auth::user();
        if ($user && !isset($this->progress[$lesson->id])) {
            $user->completedLessons()->attach($lesson->id, ['completed_at' => now()]);
            $this->progress[$lesson->id] = true;
            session()->flash('message', 'Lección marcada como completada!');
        }
    }

    public function getLessonVideoUrlProperty()
    {
        if ($this->currentLesson && $this->currentLesson->video_path) {
            return Storage::url($this->currentLesson->video_path);
        }
        return null;
    }

    public function render()
    {
        return view('livewire.user-panel.show-course');
    }
}
