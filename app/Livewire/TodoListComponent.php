<?php

namespace App\Livewire;

use App\Models\Todo;
use Livewire\Component;
use Livewire\WithPagination;

class TodoListComponent extends Component
{
    use WithPagination;
    public $name;
    public $search;
    public function create()
    {
        $validated = $this->validate([
            'name' => ['required', 'min:2', 'max:50']
        ]);
        Todo::create($validated);
        $this->reset('name');
        session()->flash('success', 'Created.');
    }
    public function delete($todoId)
    {
        Todo::find($todoId)->delete();
    }
    public function toggle($todoId)
    {
        $todo = Todo::find($todoId);
        $todo->update([
            'completed' => !$todo->completed
        ]);
    }
    public function render()
    {
        return view('livewire.todo-list-component', [
            'todos' => Todo::latest()->where('name', 'LIKE', "%{$this->search}%")->paginate(5)
        ]);
    }
}
