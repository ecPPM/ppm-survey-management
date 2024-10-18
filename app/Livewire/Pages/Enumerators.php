<?php

namespace App\Livewire\Pages;

use App\Models\User;
use App\Models\UserAttribute;
use App\Models\UserAttributeValue;
use Illuminate\Support\Facades\Hash;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Enumerators extends Component
{
    use LivewireAlert;

    public $survey;
    public $userAttributes = [];
    public $formData = [];
    public $enumCode = "";

    public $createEnumeratorModalOpen = false;
    public $editEnumeratorModalOpen = false;
    public $deleteEnumeratorModalOpen = false;

    protected $listeners = ['toggleEditEnumeratorModal', 'toggleDeleteEnumeratorModal'];

    public function mount($survey)
    {
        $this->survey = $survey;

        // Fixed fields from User model
        $this->formData = [
            'enum_code' => '',
            'name' => '',
            'mode' => '',
        ];

        // Fetch dynamic userAttributes for the form
        $this->userAttributes = UserAttribute::where('survey_id', $this->survey->id)
            ->orderByRaw('CASE WHEN `order` = 0 THEN 1 ELSE 0 END, `order` ASC, `name` ASC')
            ->get();

        // Initialize formData with empty values for each attribute
        foreach ($this->userAttributes as $userAttribute) {
            $this->formData[$userAttribute->name] = '';
        }
    }

    public function toggleCreateEnumeratorModal()
    {
        $this->createEnumeratorModalOpen = !$this->createEnumeratorModalOpen;
    }

    public function clearEnumeratorCreate()
    {
        $this->reset(['attributes', 'formData', 'createEnumeratorModalOpen']);
    }

    public function saveEnumerator()
    {
        // Validation rules for static fields
        $rules = [
            'formData.enum_code' => 'required|string',
            'formData.name' => 'required|string',
            'formData.mode' => 'required|in:None,Register,Deploy,Both',
        ];

        // Dynamically add rules for user attributes marked as required
        foreach ($this->userAttributes as $userAttribute) {
            if ($userAttribute->is_required) {
                $rules['formData.' . $userAttribute->name] = 'required';
            }
        }

        // Custom error messages
        $customMessages = [
            'required' => 'This field is required.',
        ];

        // Validate the form data with custom messages
        $this->validate($rules, $customMessages);

        $exists = User::where('enum_code', $this->formData['enum_code'])->exists();

        if ($exists) {
            // Show error alert if the 'enum_code' already exists
            $this->alert('error', 'This Enumerator Code already exists!', [
                'customClass' => [
                    'popup' => 'text-sm',
                ]
            ]);
            return;
        }

        // Save new enumerator in 'users' table
        $user = User::create([
            'enum_code' => $this->formData['enum_code'],
            'name' => $this->formData['name'],
            'mode' => $this->formData['mode'],
            'survey_id' => $this->survey->id,
            'role_id' => 2,  // Enumerator role
            'password' => Hash::make('password'),  // Default password (can be updated later)
        ]);

        // Save dynamic attributes in 'user_attribute_values' table
        foreach ($this->userAttributes as $userAttribute) {
            $value = $this->formData[$userAttribute->name];

            // Only save if the value is not empty
            if ($value !== "") {
                UserAttributeValue::create([
                    'user_id' => $user->id,  // The newly created user
                    'user_attribute_id' => $userAttribute->id,  // Attribute ID
                    'value' => $value,  // The value from form data
                ]);
            }
        }

        // Show success alert
        $this->alert('success', 'Enumerator saved successfully!', [
            'customClass' => [
                'popup' => 'text-sm',
            ]
        ]);

        // Clear form data after saving
        $this->clearEnumeratorCreate();
        $this->dispatch('refreshTable');
    }

    public function toggleEditEnumeratorModal($enumCode)
    {
        $this->editEnumeratorModalOpen = !$this->editEnumeratorModalOpen;
        if ($this->editEnumeratorModalOpen) {
            $this->setEnumeratorDetails($enumCode);
        }
    }

    public function clearEnumeratorEdit()
    {
        $this->reset(['attributes', 'formData', 'editEnumeratorModalOpen']);
    }

    public function setEnumeratorDetails($enumCode)
    {
        // Find the enumerator by ID in the users table
        $user = User::where('enum_code', $enumCode)->firstOrFail();

        // Set the static fields (enum_code, name, mode) to form data
        $this->formData['enum_code'] = $user->enum_code;
        $this->formData['name'] = $user->name;
        $this->formData['mode'] = $user->mode;

        // Load dynamic fields from user_attribute_values table
        $userAttributeValues = UserAttributeValue::where('user_id', $user->id)->get();

        // Loop through the dynamic attributes and populate formData
        foreach ($userAttributeValues as $userAttributeValue) {
            // Find the corresponding attribute name from userAttribute Model
            $attribute = UserAttribute::firstWhere('id', $userAttributeValue->user_attribute_id);
            if ($attribute) {
                $this->formData[$attribute->name] = $userAttributeValue->value;
            }
        }
    }

    public function updateEnumerator()
    {
        // Validation rules for static fields
        $rules = [
            'formData.enum_code' => 'required|string',
            'formData.name' => 'required|string',
            'formData.mode' => 'required|in:None,Register,Deploy,Both',
        ];

        // Dynamically add rules for user attributes marked as required
        foreach ($this->userAttributes as $userAttribute) {
            if ($userAttribute->is_required) {
                $rules['formData.' . $userAttribute->name] = 'required';
            }
        }

        // Custom error messages
        $customMessages = [
            'required' => 'This field is required.',
        ];

        // Validate the form data with custom messages
        $this->validate($rules, $customMessages);

        $user = User::where('enum_code', $this->formData['enum_code'])->firstOrFail();

        // Update the static fields in the `users` table
        $user->update([
            'name' => $this->formData['name'],
            'mode' => $this->formData['mode'],
        ]);

        // Update or delete dynamic fields in `user_attribute_values` table
        foreach ($this->userAttributes as $userAttribute) {
            $value = $this->formData[$userAttribute->name];

            // If the value is empty, delete the corresponding record
            if ($value === "") {
                UserAttributeValue::where('user_id', $user->id)
                    ->where('user_attribute_id', $userAttribute->id)
                    ->delete();
            } else {
                // If the value is not empty, update or create the record
                UserAttributeValue::updateOrCreate(
                    [
                        'user_id' => $user->id,  // Reference the user
                        'user_attribute_id' => $userAttribute->id,  // Reference the attribute
                    ],
                    [
                        'value' => $value,  // Set the new value
                    ]
                );
            }
        }

        $this->alert('success', 'Enumerator updated successfully!', [
            'customClass' => [
                'popup' => 'text-sm',
            ]
        ]);

        $this->clearEnumeratorEdit();
        $this->dispatch('refreshTable');
    }

    public function toggleDeleteEnumeratorModal($enumCode)
    {
        $this->enumCode = $enumCode;
        $this->deleteEnumeratorModalOpen = !$this->deleteEnumeratorModalOpen;
    }

    public function clearEnumeratorDelete()
    {
        $this->reset(['enumCode', 'deleteEnumeratorModalOpen']);
    }

    public function deleteEnumerator($enumCode)
    {
        $this->alert('success', 'Not yet implemented', [
            'customClass' => [
                'popup' => 'text-sm',
            ]
        ]);

        $this->clearEnumeratorDelete();
        $this->dispatch('refreshTable');
    }

    public function render()
    {
        return view('livewire.pages.enumerators', [
            'attributes' => $this->attributes,
        ]);
    }
}
