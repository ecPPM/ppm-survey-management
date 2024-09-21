<?php

namespace App\Livewire\Pages;

use App\Models\Survey;
use Livewire\Component;
use Livewire\WithPagination;

class Surveys extends Component
{
    use WithPagination;
    public $search;
    public $selectedStatus = '';

    // public function updatingSearch(): void
    // {
    //     $this->resetPage();
    // }

    public function render()
    {
        $surveys = Survey::latest()
                        ->when($this->selectedStatus, function($query) {
                            $query->where('status', $this->selectedStatus);
                        })
                        ->where('title', 'like', "%{$this->search}%")
                        ->paginate(5);


        $statuses = [
            'Preparation',
            'Registration',
            'Deployment',
            'Over'
        ];

        //$surveys = Survey::where('title', 'like', "%{$this->search}%");
        return view('livewire.pages.surveys',[
            'surveys' => $surveys,
            'statuses' => $statuses,
        ]);
    }
}
