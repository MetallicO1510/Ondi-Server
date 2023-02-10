<?php

namespace App\Http\Livewire;

use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class CategoriesComponent extends Component
{
    use WithPagination, WithFileUploads;
    protected $paginationTheme = 'bootstrap';
    public $name, $position = 0, $main = 0, $image, $catId;
    public $editMode = false;

    public function render()
    {
        $categories = Category::paginate(10);
        return view('livewire.categories-component', [
            'categories' => $categories
        ])->extends('layouts.app')->section('content');
    }

    public function save()
    {
        try {
            DB::beginTransaction();
            $this->validate([
                'name' => 'required',
                'position' => 'required',
                'main' => 'required',
            ]);
            $catNew = [];
            $catNew['name'] = $this->name;
            $catNew['position'] = $this->position;
            $catNew['main'] = $this->main;
            if (!$this->editMode) {
                $filename = 'default-image.jpg';
                if ($this->image) {
                    $filename = $this->storeImages();
                }
                $catNew['image'] = $filename;
                Category::create($catNew);
            } else {
                $cat = Category::find($this->catId);
                $filename = $cat->image;
                if ($this->image) {
                    $filename = $this->storeImages();
                }
                $catNew['image'] = $filename;
                $cat->update($catNew);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            Log::error('Line => ' . $e->getLine() . ' Message => ' . $e->getMessage());
            return false;
        }
        session()->flash('message', 'Guardado correctamente.');
        $this->emit('reset');
        $this->reset();

        // return redirect()->to('/categories');
    }

    public function delete()
    {
        try {
            $cat = Category::find($this->catId);
            $cat->delete();
        } catch (\Exception $e) {
            Log::error('Line => ' . $e->getLine() . ' Message => ' . $e->getMessage());
            return false;
        }
        session()->flash('message', 'Eliminado correctamente.');
        $this->emit('reset');
        $this->reset();
    }

    public function setId($id)
    {
        $this->catId = $id;
        $this->emit('showModal');
    }

    public function setEditMode(Category $cat)
    {
        $this->catId = $cat->id;
        $this->name = $cat->name;
        $this->position = $cat->position;
        $this->main = $cat->main;
        $this->editMode = true;
    }

    public function resetear()
    {
        $this->emit('reset');
        $this->reset();
    }

    function storeImages()
    {
        try {
            $extension = $this->image->getClientOriginalExtension();
            $filename = Str::slug($this->name) . '.' . $extension;
            $this->image->storeAs('public/categories', $filename);
        } catch (\Exception $e) {
            Log::error('Line => ' . $e->getLine() . ' Message => ' . $e->getMessage());
            return 'default-image.jpg';
        }

        return $filename;
    }
}
