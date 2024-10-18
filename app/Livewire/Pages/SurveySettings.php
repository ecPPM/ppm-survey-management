<?php

namespace App\Livewire\Pages;

use App\Models\Optionable;
use App\Models\RespondentAttribute;
use App\Models\Survey;
use App\Models\UserAttribute;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;

class SurveySettings extends Component
{
    use LivewireAlert;
    use WithFileUploads;

    public $survey;

    // Edit

    public $title;

    public $startDate;

    public $endDate;

    public $status;

    public $modalEditOpen = false;

    // Delete
    public $modalDeleteOpen = false;

    // Upload Enumerator list
    public $file;

    // Add Enumerator Attribute
    public $name;

    public $order;

    public $displayText;

    public $fieldType = 'Text';

    public $isRequired = 'Yes';

    public $optionDisplayText;
    public $optionValue;

    public $options = [];

    // create-enumerator-attribute-modal
    public $createEnumeratorAttributeModalOpen = false;

    // create-respondent-attribute-modal
    public $createRespondentAttributeModalOpen = false;

    // -------------------------------- Methods ------------------------------
    // Initializing the survey
    public function mount($survey)
    {
        $this->survey = $survey;
        $this->title = $survey->title;
        $this->startDate = $survey->start_date;
        $this->endDate = $survey->end_date;
        $this->status = $survey->status;
    }

    // Edit
    public function toggleEditModal()
    {
        $this->modalEditOpen = !$this->modalEditOpen;
    }

    public function clearSurveyEdit()
    {
        $this->reset(['title', 'startDate', 'endDate', 'status', 'modalEditOpen']);
        $this->title = $this->survey->title;
        $this->startDate = $this->survey->start_date;
        $this->endDate = $this->survey->end_date;
        $this->status = $this->survey->status;
    }

    public function editSurvey()
    {
        $survey = Survey::find($this->survey->id);
        $this->validate([
            'title' => 'required|string|max:255',
            'startDate' => 'required|string|max:255',
            'endDate' => 'required|string|max:255',
            'status' => 'required|string|max:255',
        ]);

        if ($survey) {
            $survey->update([
                'title' => $this->title,
                'start_date' => $this->startDate,
                'end_date' => $this->endDate,
                'status' => $this->status,
            ]);

            $survey = Survey::find($this->survey->id);
            $this->mount($survey);
            $this->updateEnumeratorMode($survey);

            $this->alert('success', 'Saved Changes!', [
                'customClass' => [
                    'popup' => 'text-sm',
                ]
            ]);
        } else {
            $this->alert('error', 'Something went wrong.', [
                'customClass' => [
                    'popup' => 'text-sm',
                ]
            ]);
        }

        $this->clearSurveyEdit();
    }

    public function updateEnumeratorMode($survey)
    {
        if ($survey->status === 'Registration') {
            $survey->users()->where('role_id', 2)->update(['mode' => 'Register']);
        } elseif ($survey->status === 'Deployment') {
            $survey->users()->where('role_id', 2)->update(['mode' => 'Deploy']);
        } else {
            $survey->users()->where('role_id', 2)->update(['mode' => 'None']);
        }
    }
    // Delete
    public function toggleDeleteModal()
    {
        $this->modalDeleteOpen = !$this->modalDeleteOpen;
    }

    // Add Enumerator Attribute
    public function toggleCreateEnumeratorAttributeModal()
    {
        $this->createEnumeratorAttributeModalOpen = !$this->createEnumeratorAttributeModalOpen;
    }

    public function clearEnumAttributeCreate()
    {
        $this->reset(['name', 'order', 'displayText', 'fieldType', 'isRequired', 'optionDisplayText', 'optionValue', 'options', 'createEnumeratorAttributeModalOpen']);
        $this->resetValidation();
    }

    public function saveEnumeratorAttribute()
    {
        $this->validate([
            'order' => 'required',
            'displayText' => 'required',
            'fieldType' => 'required',
            'isRequired' => 'required',
            'name' => [
                'required',
                function ($attribute, $value, $fail) {
                    $nameColumns = UserAttribute::where('survey_id', $this->survey->id)
                        ->pluck('name')
                        ->toArray();

                    if (in_array($value, $nameColumns)) {
                        $fail("$value column already exists");
                    }
                },
            ],
            'options.*.displayText' => 'required|string|min:1',
            'options.*.value' => 'required|string|min:1',
            'options' => [
                function ($attribute, $value, $fail) {
                    // Check if there are at least two options
                    if ($this->fieldType !== 'Text' && count($value) < 2) {
                        $fail("Please provide at least two options.");
                    }
                },
            ],
        ]);

        $userAttribute = UserAttribute::create([
            'survey_id' => $this->survey->id,
            'name' => $this->name,
            'display_text' => $this->displayText,
            'order' => $this->order,
            'is_required' => $this->isRequired === 'Yes',
            'field_type' => $this->fieldType,
        ]);

        // If the field type is not 'Text', store the options
        if ($this->fieldType !== 'Text') {
            foreach ($this->options as $option) {
                $userAttribute->options()->create([
                    'display_text' => $option['displayText'],
                    'value' => $option['value'],
                ]);
            }
        }

        $this->alert('success', 'Added New Attribute!', [
            'customClass' => [
                'popup' => 'text-sm',
            ]
        ]);

        $this->clearEnumAttributeCreate();

        return redirect(request()->header('Referer'));
    }

    public function addOption()
    {
        $this->validate([
            'optionDisplayText' => 'required|string|max:255',
            'optionValue' => 'required|string|max:255',
        ]);

        $this->options[] = [
            'displayText' => $this->optionDisplayText,
            'value' => $this->optionValue
        ];

        $this->reset(['optionDisplayText', 'optionValue']);
    }

    public function removeOption($index)
    {
        unset($this->options[$index]);
        $this->options = array_values($this->options);  // Reindex the array to avoid issues
    }

    // Add Respondent Attribute
    public function toggleCreateRespondentAttributeModal()
    {
        $this->createRespondentAttributeModalOpen = !$this->createRespondentAttributeModalOpen;
    }

    public function clearRespAttributeCreate()
    {
        $this->reset(['name', 'order', 'displayText', 'fieldType', 'isRequired', 'optionDisplayText', 'optionValue', 'options', 'createRespondentAttributeModalOpen']);
        $this->resetValidation();
    }

    public function saveRespondentAttribute()
    {
        $this->validate([
            'order' => 'required',
            'displayText' => 'required',
            'fieldType' => 'required',
            'isRequired' => 'required',
            'name' => [
                'required',
                function ($attribute, $value, $fail) {
                    $nameColumns = RespondentAttribute::where('survey_id', $this->survey->id)
                        ->pluck('name')
                        ->toArray();

                    if (in_array($value, $nameColumns)) {
                        $fail("$value column already exists");
                    }
                },
            ],
            'options.*.displayText' => 'required|string|min:1',
            'options.*.value' => 'required|string|min:1',
            'options' => [
                function ($attribute, $value, $fail) {
                    // Check if there are at least two options
                    if ($this->fieldType !== 'Text' && count($value) < 2) {
                        $fail("Please provide at least two options.");
                    }
                },
            ],
        ]);

        $respondentAttribute = RespondentAttribute::create([
            'survey_id' => $this->survey->id,
            'name' => $this->name,
            'display_text' => $this->displayText,
            'order' => $this->order,
            'is_required' => $this->isRequired === 'Yes',
            'field_type' => $this->fieldType,
        ]);

        // If the field type is not 'Text', store the options
        if ($this->fieldType !== 'Text') {
            foreach ($this->options as $option) {
                $respondentAttribute->options()->create([
                    'display_text' => $option['displayText'],
                    'value' => $option['value'],
                ]);
            }
        }

        $this->alert('success', 'Added New Attribute!', [
            'customClass' => [
                'popup' => 'text-sm',
            ]
        ]);

        $this->clearRespAttributeCreate();

        return redirect(request()->header('Referer'));
    }

    public function render()
    {
        return view('livewire.pages.survey-settings');
    }
}
