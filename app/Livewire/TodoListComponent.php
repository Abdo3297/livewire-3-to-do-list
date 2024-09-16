<?php

namespace App\Livewire;

use App\Models\Todo;
use Exception;
use Livewire\Component;
use Livewire\WithPagination;

class TodoListComponent extends Component
{
    use WithPagination;
    public $name;
    public $search;
    public $editingTodoID;
    public $editingTodoName;
    public function create()
    {
        $validated = $this->validate([
            'name' => ['required', 'min:2', 'max:50']
        ]);
        Todo::create($validated);
        $this->reset('name');
        session()->flash('success', 'Created.');
        $this->resetPage();
    }
    public function delete($todoId)
    {
        try {
            Todo::findOrFail($todoId)->delete();
        } catch(Exception $e) {
            session()->flash('error', 'Failed to delete todo!');
            return;
        }
        
    }
    public function toggle($todoId)
    {
        $todo = Todo::find($todoId);
        $todo->update([
            'completed' => !$todo->completed
        ]);
    }
    public function edit($todoId)
    {
        $this->editingTodoID = $todoId;
        $this->editingTodoName = Todo::find($todoId)->name;
    }
    public function cancelEdit()
    {
        $this->reset('editingTodoID','editingTodoName');
    }
    public function update()
    {
        $validated = $this->validate([
            'editingTodoName' => ['required', 'min:2', 'max:50']
        ]);
        Todo::find($this->editingTodoID)->update([
            'name' => $validated['editingTodoName']
        ]);
        $this->cancelEdit();
    }
    public function render()
    {
        return view('livewire.todo-list-component', [
            'todos' => Todo::latest()->where('name', 'LIKE', "%{$this->search}%")->paginate(5)
        ]);
    }
}
