<?php

namespace App\Livewire\AdminPanel\Lms;

use App\Models\Course;
use App\Models\CourseSection;
use App\Models\Lesson;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

#[Layout('layouts.admin')]
class EditCourse extends Component
{
    use WithFileUploads;

    public Course $course;
    public $sections = [];
    public $courseTitle;
    public $courseDescription;
    public $courseImageUrl;
    public $courseIsPremium;
    public $courseStatus;

    // For new section
    public $newSectionTitle = '';

    // For editing existing section
    public ?CourseSection $editingSection = null;
    public $editingSectionTitle = '';

    // For new lesson
    public $newLessonSectionId;
    public $newLessonTitle = '';
    public $newLessonVideoFile;
    public $newLessonContent;

    // For editing existing lesson
    public ?Lesson $editingLesson = null;
    public $editingLessonTitle = '';
    public $editingLessonVideoFile;
    public $editingLessonContent;
    public $editingLessonCurrentVideoPath;

    public function mount(Course $course)
    {
        $this->course = $course;
        $this->courseTitle = $course->title;
        $this->courseDescription = $course->description;
        $this->courseImageUrl = $course->image_url;
        $this->courseIsPremium = $course->is_premium;
        $this->courseStatus = $course->status;
        $this->loadSectionsAndLessons();
    }

    public function loadSectionsAndLessons()
    {
        $this->sections = $this->course->sections()
            ->with(['lessons' => function ($query) {
                $query->orderBy('order', 'asc');
            }])
            ->orderBy('order', 'asc')
            ->get()
            ->toArray();
    }

    protected function rules()
    {
        return [
            'courseTitle' => 'required|string|max:255',
            'courseDescription' => 'nullable|string',
            'courseImageUrl' => 'nullable|url|max:255',
            'courseIsPremium' => 'boolean',
            'courseStatus' => ['required', Rule::in(['draft', 'published', 'archived'])],
            'newSectionTitle' => 'nullable|string|max:255',
            'editingSectionTitle' => 'nullable|string|max:255',
            'newLessonTitle' => 'nullable|string|max:255',
            'newLessonVideoFile' => 'nullable|file|mimetypes:video/mp4,video/mpeg,video/quicktime|max:4096000', // 4GB Max
            'newLessonContent' => 'nullable|string',
            'editingLessonTitle' => 'nullable|string|max:255',
            'editingLessonVideoFile' => 'nullable|file|mimetypes:video/mp4,video/mpeg,video/quicktime|max:4096000',
            'editingLessonContent' => 'nullable|string',
        ];
    }

    // Course Details Save
    public function saveCourseDetails()
    {
        $this->validate([
            'courseTitle' => 'required|string|max:255',
            'courseDescription' => 'nullable|string',
            'courseImageUrl' => 'nullable|url|max:255',
            'courseIsPremium' => 'boolean',
            'courseStatus' => ['required', Rule::in(['draft', 'published', 'archived'])],
        ]);

        $this->course->update([
            'title' => $this->courseTitle,
            'slug' => Str::slug($this->courseTitle),
            'description' => $this->courseDescription,
            'image_url' => $this->courseImageUrl,
            'is_premium' => $this->courseIsPremium,
            'status' => $this->courseStatus,
        ]);

        session()->flash('message', 'Detalles del curso actualizados.');
    }

    // Section Management
    public function addSection()
    {
        $this->validate(['newSectionTitle' => 'required|string|max:255']);

        $order = $this->course->sections()->max('order') + 1;

        $this->course->sections()->create([
            'title' => $this->newSectionTitle,
            'order' => $order,
        ]);

        $this->newSectionTitle = '';
        $this->loadSectionsAndLessons();
        session()->flash('message', 'Sección añadida.');
    }

    public function editSection(CourseSection $section)
    {
        $this->editingSection = $section;
        $this->editingSectionTitle = $section->title;
    }

    public function updateSection()
    {
        $this->validate(['editingSectionTitle' => 'required|string|max:255']);

        $this->editingSection->update([
            'title' => $this->editingSectionTitle,
        ]);

        $this->editingSection = null;
        $this->editingSectionTitle = '';
        $this->loadSectionsAndLessons();
        session()->flash('message', 'Sección actualizada.');
    }

    public function deleteSection(CourseSection $section)
    {
        $section->delete();
        $this->loadSectionsAndLessons();
        session()->flash('message', 'Sección eliminada.');
    }

    // Lesson Management
    public function addLesson($sectionId)
    {
        $this->reset(['newLessonTitle', 'newLessonVideoFile', 'newLessonContent']);
        $this->newLessonSectionId = $sectionId;
        $this->dispatch('show-new-lesson-form', $sectionId);
    }

    public function saveNewLesson()
    {
        $this->validate([
            'newLessonSectionId' => 'required|exists:course_sections,id',
            'newLessonTitle' => 'required|string|max:255',
            'newLessonVideoFile' => 'required|file|mimetypes:video/mp4,video/mpeg,video/quicktime|max:4096000',
            'newLessonContent' => 'nullable|string',
        ]);

        $path = $this->newLessonVideoFile->store('lms/videos/' . $this->course->slug, 'public');

        $section = CourseSection::find($this->newLessonSectionId);
        $order = $section->lessons()->max('order') + 1;

        $section->lessons()->create([
            'title' => $this->newLessonTitle,
            'slug' => Str::slug($this->newLessonTitle),
            'video_path' => $path,
            'content' => $this->newLessonContent,
            'order' => $order,
        ]);

        $this->reset(['newLessonSectionId', 'newLessonTitle', 'newLessonVideoFile', 'newLessonContent']);
        $this->loadSectionsAndLessons();
        session()->flash('message', 'Lección añadida.');
        $this->dispatch('lessons-updated', ['lessons' => collect($this->sections)->firstWhere('id', $section->id)['lessons'], 'sectionId' => $section->id]);
    }

    public function editLesson(Lesson $lesson)
    {
        $this->editingLesson = $lesson;
        $this->editingLessonTitle = $lesson->title;
        $this->editingLessonContent = $lesson->content;
        $this->editingLessonCurrentVideoPath = $lesson->video_path;
        $this->reset('editingLessonVideoFile'); // Clear any previously selected file
        $this->dispatch('show-edit-lesson-form', $lesson->id);
    }

    public function updateLesson()
    {
        $rules = [
            'editingLessonTitle' => 'required|string|max:255',
            'editingLessonContent' => 'nullable|string',
        ];

        if ($this->editingLessonVideoFile) {
            $rules['editingLessonVideoFile'] = 'file|mimetypes:video/mp4,video/mpeg,video/quicktime|max:4096000';
        }

        $this->validate($rules);

        $videoPath = $this->editingLessonCurrentVideoPath;
        if ($this->editingLessonVideoFile) {
            // Delete old video if it exists
            if ($this->editingLessonCurrentVideoPath) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($this->editingLessonCurrentVideoPath);
            }
            $videoPath = $this->editingLessonVideoFile->store('lms/videos/' . $this->course->slug, 'public');
        }

        $sectionId = $this->editingLesson->course_section_id;
        $this->editingLesson->update([
            'title' => $this->editingLessonTitle,
            'slug' => Str::slug($this->editingLessonTitle),
            'video_path' => $videoPath,
            'content' => $this->editingLessonContent,
        ]);

        $this->editingLesson = null;
        $this->reset(['editingLessonTitle', 'editingLessonVideoFile', 'editingLessonContent', 'editingLessonCurrentVideoPath']);
        $this->loadSectionsAndLessons();
        session()->flash('message', 'Lección actualizada.');
        $this->dispatch('lessons-updated', ['lessons' => collect($this->sections)->firstWhere('id', $sectionId)['lessons'], 'sectionId' => $sectionId]);
        $this->dispatch('lesson-updated-hide-form');
    }

    public function deleteLesson(Lesson $lesson)
    {
        $sectionId = $lesson->course_section_id;
        // Delete associated video file
        if ($lesson->video_path) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($lesson->video_path);
        }
        $lesson->delete();
        $this->loadSectionsAndLessons();
        session()->flash('message', 'Lección eliminada.');
        $this->dispatch('lessons-updated', ['lessons' => collect($this->sections)->firstWhere('id', $sectionId)['lessons'] ?? [], 'sectionId' => $sectionId]);
    }

    public function reorderSections($sections)
    {
        foreach ($sections as $section) {
            CourseSection::find($section['value'])->update(['order' => $section['order']]);
        }
        $this->loadSectionsAndLessons();
    }

    public function reorderLessons($lessons, $sectionId)
    {
        \Illuminate\Support\Facades\DB::transaction(function () use ($lessons) {
            foreach ($lessons as $lessonData) {
                \Illuminate\Support\Facades\DB::table('lessons')
                    ->where('id', $lessonData['value'])
                    ->update(['order' => $lessonData['order']]);
            }
        });

        $this->loadSectionsAndLessons();

        $updatedSection = collect($this->sections)->firstWhere('id', $sectionId);
        $this->dispatch('lessons-updated', ['lessons' => $updatedSection ? $updatedSection['lessons'] : [], 'sectionId' => $sectionId]);
    }

    public function render()
    {
        return view('livewire.admin-panel.lms.edit-course');
    }
}
