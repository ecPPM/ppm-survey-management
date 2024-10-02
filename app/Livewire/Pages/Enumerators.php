<?php

namespace App\Livewire\Pages;

use Livewire\Component;

class Enumerators extends Component
{
    public $survey;

    public function mount($survey)
    {
        $this->survey = $survey;
    }

    public function render()
    {
        return view('livewire.pages.enumerators');
    }
}
