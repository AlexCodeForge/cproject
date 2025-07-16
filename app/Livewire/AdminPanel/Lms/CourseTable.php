<?php

namespace App\Livewire\AdminPanel\Lms;

use App\Models\Course;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;
use Livewire\Attributes\On;

class CourseTable extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $isPremiumFilter = '';

    // Properties for Create/Edit Modal
    public $showCourseModal = false;
    public ?Course $editingCourse;
    public $title = '';
    public $description = '';
    public $is_premium = false;
    public $status = 'draft';

    #[On('course-deleted')]
    public function refreshComponent()
    {
        // This method can be empty. Its purpose is to be a target for the event,
        // which forces Livewire to re-render the component.
    }

    protected function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_premium' => 'boolean',
            'status' => 'required|in:draft,published,archived',
        ];
    }

    protected function messages()
    {
        return [
            'title.required' => 'El título del curso es obligatorio.',
            'title.string' => 'El título del curso debe ser una cadena de texto.',
            'title.max' => 'El título del curso no debe exceder los :max caracteres.',
            'description.string' => 'La descripción debe ser una cadena de texto.',
            'is_premium.boolean' => 'El valor de "es premium" debe ser verdadero o falso.',
            'status.required' => 'El estado del curso es obligatorio.',
            'status.in' => 'El estado del curso seleccionado no es válido.',
        ];
    }

    public function create()
    {
        $this->reset(['title', 'description', 'is_premium', 'status']);
        $this->editingCourse = new Course(['status' => 'draft', 'is_premium' => false]);
        $this->showCourseModal = true;
    }

    public function edit(Course $course)
    {
        $this->editingCourse = $course;
        $this->title = $course->title;
        $this->description = $course->description;
        $this->is_premium = $course->is_premium;
        $this->status = $course->status;
        $this->showCourseModal = true;
    }

    public function save()
    {
        try {
            $this->validate();
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->dispatch('showErrorModal', message: collect($e->errors())->flatten()->toArray(), title: 'Error de Validación');
            throw $e; // Re-throw the exception to ensure individual field errors are displayed
        }

        $this->editingCourse->fill([
            'title' => $this->title,
            'slug' => Str::slug($this->title),
            'description' => $this->description,
            'is_premium' => $this->is_premium,
            'status' => $this->status,
        ]);

        $this->editingCourse->save();

        $this->showCourseModal = false;
        $this->dispatch('showSuccessModal', 'El curso ha sido guardado correctamente.');

        $this->reset('title', 'description', 'is_premium', 'status', 'editingCourse');
    }

    public function render()
    {
        $courses = Course::query()
            ->when($this->search, function ($query) {
                $query->where('title', 'like', '%' . $this->search . '%');
            })
            ->when($this->statusFilter, function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->when($this->isPremiumFilter !== '', function ($query) {
                $query->where('is_premium', (bool) $this->isPremiumFilter);
            })
            ->paginate(10);

        $totalCourses = Course::count();
        $publishedCourses = Course::where('status', 'published')->count();
        $premiumCourses = Course::where('is_premium', true)->count();
        $draftCourses = Course::where('status', 'draft')->count();

        return view('livewire.admin-panel.lms.course-table', [
            'courses' => $courses,
            'totalCourses' => $totalCourses,
            'publishedCourses' => $publishedCourses,
            'premiumCourses' => $premiumCourses,
            'draftCourses' => $draftCourses,
        ]);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingIsPremiumFilter()
    {
        $this->resetPage();
    }

    public function confirmCourseDeletion($courseId)
    {
        $this->dispatch('showConfirmationModal',
            title: 'Eliminar Curso',
            message: '¿Estás seguro de que quieres eliminar este curso? Esta acción no se puede deshacer.\n\n¡ADVERTENCIA! Esto eliminará permanentemente todos los capítulos y lecciones asociados a este curso.',
            confirmAction: 'delete-course',
            params: ['courseId' => $courseId]
        );
    }

    #[On('delete-course')]
    public function deleteCourse(int $courseId)
    {
        $course = Course::find($courseId);
        if ($course) {
            $course->delete();
        }

        $this->dispatch('showSuccessModal', 'El curso ha sido eliminado correctamente.');

        $this->resetPage();
        $this->dispatch('course-deleted')->self();
    }
}
