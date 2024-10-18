<?php

namespace App\Livewire\Pages;

use App\Models\Respondent;
use App\Models\RespondentAttribute;
use App\Models\RespondentAttributeValue;
use App\Models\Survey;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Registration extends Component
{
    use LivewireAlert;

    public $survey;
    public $respAttributes = [];
    public $formData = [];

    public $respondent;

    public $createRespondentModalOpen = false;
    public $editRespondentModalOpen = false;
    public $deleteRespondentModalOpen = false;

    public function mount()
    {
        $this->survey = Survey::find(Auth::user()->survey_id);
        $this->formData = [
            'name' => '',
            'password' => '',
        ];

        $this->respAttributes = RespondentAttribute::where('survey_id', $this->survey->id)
            ->orderByRaw('CASE WHEN `order` = 0 THEN 1 ELSE 0 END, `order` ASC, `name` ASC')
            ->get();

        // Initialize formData with empty values for each attribute
        foreach ($this->respAttributes as $respAttribute) {
            $this->formData[$respAttribute->name] = '';
        }
    }

    public function toggleCreateRespondentModal()
    {
        $this->createRespondentModalOpen = !$this->createRespondentModalOpen;
    }

    public function clearRespondentCreate()
    {
        $this->reset(['formData', 'createRespondentModalOpen']);
    }

    public function saveRespondent()
    {
        $rules = [
            'formData.name' => 'required|string',
            'formData.password' => 'required|string',
        ];

        // Dynamically add rules for user attributes marked as required
        foreach ($this->respAttributes as $respAttribute) {
            if ($respAttribute->is_required) {
                $rules['formData.' . $respAttribute->name] = 'required';
            }
        }

        // Custom error messages
        $customMessages = [
            'required' => 'This field is required.',
        ];

        // Validate the form data with custom messages
        $this->validate($rules, $customMessages);

        // save the respondent
        $respondent = Respondent::create([
            'resp_code' => Auth::user()->enum_code . 'T' . now()->format('dHis'),
            'name' => $this->formData['name'],
            'password' => $this->formData['password'],
            'survey_id' => $this->survey->id,
            'user_id' => Auth::id(),
            'status' => "blank"
        ]);

        // Save dynamic attributes in 'respondent_attribute_values' table
        foreach ($this->respAttributes as $respAttribute) {
            $value = $this->formData[$respAttribute->name];

            // Only save if the value is not empty
            if ($value !== "") {
                RespondentAttributeValue::create([
                    'respondent_id' => $respondent->id,  // The newly created respondent
                    'respondent_attribute_id' => $respAttribute->id,  // Attribute ID
                    'value' => $value,  // The value from form data
                ]);
            }
        }

        // Show success alert
        $this->alert('success', 'Respondent saved successfully!', [
            'customClass' => [
                'popup' => 'text-sm',
            ]
        ]);

        // Clear form data after saving
        $this->clearRespondentCreate();
    }

    public function toggleEditRespondentModal($respId)
    {
        $this->editRespondentModalOpen = !$this->editRespondentModalOpen;
        if ($this->editRespondentModalOpen) {
            $this->setRespondentDetails($respId);
        }
    }

    public function clearRespondentEdit()
    {
        $this->reset(['respondent', 'formData', 'editRespondentModalOpen']);
    }

    public function setRespondentDetails($respId)
    {
        // Find the respondent by ID in the respondents table
        $this->respondent = Respondent::where('id', $respId)->first();

        $this->formData['name'] = $this->respondent->name;
        $this->formData['password'] = $this->respondent->password;

        // Load dynamic fields from user_attribute_values table
        $respAttributeValues = RespondentAttributeValue::where('respondent_id', $this->respondent->id)->get();

        // Loop through the dynamic attributes and populate formData
        foreach ($respAttributeValues as $respAttributeValue) {
            // Find the corresponding attribute name from respAttribute Model
            $attribute = RespondentAttribute::firstWhere('id', $respAttributeValue->respondent_attribute_id);
            if ($attribute) {
                $this->formData[$attribute->name] = $respAttributeValue->value;
            }
        }
    }

    public function updateRespondent()
    {
        // Validation rules for static fields
        $rules = [
            'formData.name' => 'required|string',
            'formData.password' => 'required|string',
        ];

        // Dynamically add rules for user attributes marked as required
        foreach ($this->respAttributes as $respAttribute) {
            if ($respAttribute->is_required) {
                $rules['formData.' . $respAttribute->name] = 'required';
            }
        }

        // Custom error messages
        $customMessages = [
            'required' => 'This field is required.',
        ];

        // Validate the form data with custom messages
        $this->validate($rules, $customMessages);

        // Update the static fields in the `users` table
        $this->respondent->update([
            'name' => $this->formData['name'],
            'password' => $this->formData['password'],
        ]);

        // Update or delete dynamic fields in `user_attribute_values` table
        foreach ($this->respAttributes as $respAttribute) {
            $value = $this->formData[$respAttribute->name];

            // If the value is empty, delete the corresponding record
            if ($value === "") {
                RespondentAttributeValue::where('respondent_id', $this->respondent->id)
                    ->where('respondent_attribute_id', $respAttribute->id)
                    ->delete();
            } else {
                // If the value is not empty, update or create the record
                RespondentAttributeValue::updateOrCreate(
                    [
                        'respondent_id' => $this->respondent->id,
                        'respondent_attribute_id' => $respAttribute->id,
                    ],
                    [
                        'value' => $value,  // Set the new value
                    ]
                );
            }
        }

        $this->alert('success', 'Respondent updated successfully!', [
            'customClass' => [
                'popup' => 'text-sm',
            ]
        ]);

        $this->clearRespondentEdit();
    }

    public function toggleDeleteRespondentModal($respId)
    {
        $this->deleteRespondentModalOpen = !$this->deleteRespondentModalOpen;
        if ($this->deleteRespondentModalOpen) {
            $this->respondent = Respondent::where('id', $respId)->first();
        }
    }

    public function clearRespondentDelete()
    {
        $this->reset(['respondent', 'deleteRespondentModalOpen']);
    }

    public function deleteRespondent()
    {
        $this->alert('success', 'Not yet implemented', [
            'customClass' => [
                'popup' => 'text-sm',
            ]
        ]);

        $this->clearRespondentDelete();
    }


    public function render()
    {
        // Retrieve the current user's ID (assuming Auth user is the enumerator)
        $userId = Auth::id();

        // Get the respondents associated with this enumerator (filter by user_id)
        $respondents = Respondent::where('user_id', $userId)
            ->with(['attributeValues.respondentAttribute']) // Load respondent attributes, not options directly
            ->get();

        // Pass respondents to the view
        return view('livewire.pages.registration', [
            'respondents' => $respondents
        ]);
    }
}
