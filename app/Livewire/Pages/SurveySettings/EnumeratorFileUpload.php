<?php

namespace App\Livewire\Pages\SurveySettings;

use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;

class EnumeratorFileUpload extends Component
{
    use LivewireAlert;
    use WithFileUploads;

    public $file;
    public $surveyId;

    // Validation rules
    protected $rules = [
        'file' => 'required|mimes:xlsx,csv|max:10240', // Allowing only Excel and CSV files with a max size of 10MB
    ];

    // Method to handle the file upload
    public function upload()
    {
        $this->validate();

        $filePath = $this->file->store('enumerator-files', 'public');

        $this->alert('success', 'Added New Attribute!', [
            'customClass' => [
                'popup' => 'text-sm',
            ]
        ]);

        // Optionally reset the file input after upload
        $this->reset('file');
    }

    public function render()
    {
        return view('livewire.pages.survey-settings.enumerator-file-upload');
    }
}
