<?php

namespace App\Livewire\AdminPanel\Categories;

use App\Models\PostCategory;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;
use Livewire\Attributes\On;

class CategoryTable extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $selectedCategory;
    public $showModal = false;

    public $showCategoryModal = false;
    public ?PostCategory $editingCategory;
    public $name = '';
    public $description = '';
    public $color = '#FFFFFF';
    public $icon = '';
    public $is_active = true;
    public $sort_order = 0;

    #[On('category-deleted')]
    public function refreshComponent()
    {
    }

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:post_categories,name,' . ($this->editingCategory ? $this->editingCategory->id : 'NULL'),
            'description' => 'nullable|string',
            'color' => 'nullable|string|max:7',
            'icon' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer',
        ];
    }

    protected function messages()
    {
        return [
            'name.required' => 'El nombre de la categoría es obligatorio.',
            'name.unique' => 'Ya existe una categoría con este nombre.',
            'name.string' => 'El nombre debe ser texto.',
            'name.max' => 'El nombre no debe exceder los 255 caracteres.',
            'description.string' => 'La descripción debe ser texto.',
            'color.max' => 'El color no debe exceder los 7 caracteres.',
            'icon.max' => 'El ícono no debe exceder los 255 caracteres.',
            'is_active.boolean' => 'El campo activo debe ser verdadero o falso.',
            'sort_order.integer' => 'El orden debe ser un número.',
        ];
    }

    public function create()
    {
        $this->reset(['name', 'description', 'color', 'icon', 'is_active', 'sort_order']);
        $this->editingCategory = new PostCategory(['is_active' => true, 'color' => '#FFFFFF', 'sort_order' => 0]);
        $this->name = '';
        $this->description = '';
        $this->color = '#FFFFFF';
        $this->icon = '';
        $this->is_active = true;
        $this->sort_order = 0;
        $this->showCategoryModal = true;
    }

    public function edit(PostCategory $category)
    {
        $this->editingCategory = $category;
        $this->name = $category->name;
        $this->description = $category->description;
        $this->color = $category->color;
        $this->icon = $category->icon;
        $this->is_active = $category->is_active;
        $this->sort_order = $category->sort_order;
        $this->showCategoryModal = true;
    }

    public function save()
    {
        try {
            $this->validate();
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->dispatch('showErrorModal', message: collect($e->errors())->flatten()->toArray(), title: 'Error de Validación');
            throw $e;
        }

        $this->editingCategory->fill([
            'name' => $this->name,
            'description' => $this->description,
            'color' => $this->color,
            'icon' => $this->icon,
            'is_active' => $this->is_active,
            'sort_order' => $this->sort_order,
        ]);

        $this->editingCategory->save();

        $this->showCategoryModal = false;
        $this->dispatch('showSuccessModal', 'La categoría ha sido guardada correctamente.');
        $this->reset('name', 'description', 'color', 'icon', 'is_active', 'sort_order', 'editingCategory');
    }

    public function render()
    {
        $query = PostCategory::withCount('posts')
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('description', 'like', '%' . $this->search . '%');
            });

        if ($this->statusFilter !== '') {
            $query->where('is_active', $this->statusFilter === '1');
        }

        $categories = $query->orderBy('sort_order', 'asc')->paginate(10);

        $totalCategories = PostCategory::count();
        $activeCategories = PostCategory::where('is_active', true)->count();
        $emptyCategories = PostCategory::doesntHave('posts')->count();

        return view('admin_panel.categories.livewire.category-table', [
            'categories' => $categories,
            'totalCategories' => $totalCategories,
            'activeCategories' => $activeCategories,
            'emptyCategories' => $emptyCategories,
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

    public function viewCategory($categoryId)
    {
        $this->selectedCategory = PostCategory::with('posts')->find($categoryId);
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->selectedCategory = null;
        $this->showModal = false;
    }

    public function confirmCategoryDeletion($categoryId)
    {
        $this->dispatch('showConfirmationModal',
            title: 'Eliminar Categoría',
            message: '¿Estás seguro de que quieres eliminar esta categoría? Esta acción no se puede deshacer.',
            confirmAction: 'delete-category',
            params: ['categoryId' => $categoryId]
        );
    }

    #[On('delete-category')]
    public function deleteCategory(int $categoryId)
    {
        $category = PostCategory::find($categoryId);
        if ($category) {
            $category->delete();
        }

        $this->dispatch('showSuccessModal', 'La categoría ha sido eliminada correctamente.');
        $this->resetPage();
        $this->dispatch('category-deleted')->self();
    }
}
