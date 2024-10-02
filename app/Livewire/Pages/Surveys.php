<?php

namespace App\Livewire\Pages;

use App\Models\Survey;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class Surveys extends Component
{
    use LivewireAlert;
    use WithPagination;
    public $search;
    public $selectedStatus = '';

    // public function updatingSearch(): void
    // {
    //     $this->resetPage();
    // }

    // Form components for create
    #[Validate('required')]
    public $title;

    #[Validate('required')]
    public $startDate;

    #[Validate('required')]
    public $endDate;

    // create-modal
    public $modalOpen = false;

    public function toggleModal()
    {
        if (!$this->modalOpen) {
            //$this->meetingDate = now()->format('Y-m-d');
        }
        $this->modalOpen = !$this->modalOpen;
    }

    public function clear()
    {
        $this->reset(['title', 'startDate', 'endDate', 'modalOpen']);
        $this->resetValidation();
    }

    public function createNewSurvey()
    {
        $this->validate();

        Survey::create([
            'title' => $this->title,
            'start_date' => $this->startDate,
            'end_date' => $this->endDate,
            'status' => 'Preparation'
        ]);
        // flash success message
        $this->alert('success', 'Created New Survey!', [
            'customClass' => [
                'popup' => 'text-sm',
            ]
        ]);

        $this->clear();
    }

    public function render()
    {
        $surveys = Survey::latest()
                        ->when($this->selectedStatus, function($query) {
                            $query->where('status', $this->selectedStatus);
                        })
                        ->where('title', 'like', "%{$this->search}%")
                        ->paginate(3);


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
