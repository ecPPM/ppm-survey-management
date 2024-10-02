<?php

namespace App\Livewire\Pages;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class UserManagement extends Component
{
    use WithPagination;

    public $search = "";

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function handleRowClick($id)
    {
        return redirect()->to('/users/' . $id);
    }

    public function render()
    {
        $users = User::orderBy('enum_code', 'asc')->where('enum_code', 'like', "%{$this->search}%")->paginate(5);

        return view('livewire.pages.user-management', [
            'users' => $users,
        ]);
    }
}
