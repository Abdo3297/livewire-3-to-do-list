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
    public function create(){
        // validate
        $validated = $this->validate([
            'name'=>['required','min:2','max:50']
        ]);
        // create the todo
        Todo::create($validated);
        // clear the input
        $this->reset('name');
        // send flash message
        session()->flash('success','Created.');
    }
    public function render()
    {
        return view('livewire.todo-list-component',[
            'todos' => Todo::latest()->where('name','LIKE',"%{$this->search}%")->paginate(5)
        ]);
    }
}
