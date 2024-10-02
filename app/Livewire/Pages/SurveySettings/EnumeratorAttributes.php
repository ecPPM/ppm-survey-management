<?php

namespace App\Livewire\Pages\SurveySettings;

use App\Models\Option;
use App\Models\UserAttribute;
use App\Models\UserAttributeValue;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class EnumeratorAttributes extends Component
{
    use LivewireAlert;
    public $surveyId;
    public $editingAttributeId;

    // Edit Enumerator Attribute
    public $editName;

    public $editOrder;

    public $editDisplayText;

    public $editFieldType;

    public $editIsRequired;

    public $editOptionDisplayText;
    public $editOptionValue;

    public $editOptions = [];

    // edit
    public $editEnumeratorAttributeModalOpen = false;

    // delete
    public $deleteEnumeratorAttributeModalOpen = false;

    public function mount($id)
    {
        $this->surveyId = $id;
    }

    public function toggleEditEnumeratorAttributeModal($id)
    {
        $this->editEnumeratorAttributeModalOpen = !$this->editEnumeratorAttributeModalOpen;
        $this->editingAttributeId = $id;
        if ($this->editEnumeratorAttributeModalOpen) {
            $this->setEnumeratorAttributeDetails($id);
        } else {
            $this->clearEnumAttributeEdit();
        }
    }

    public function addOption()
    {
        $this->validate([
            'editOptionDisplayText' => 'required|string|max:255',
            'editOptionValue' => 'required|string|max:255',
        ]);

        $this->editOptions[] = [
            'display_text' => $this->editOptionDisplayText,
            'value' => $this->editOptionValue
        ];

        $this->reset(['editOptionDisplayText', 'editOptionValue']);
    }

    public function removeOption($index)
    {
        unset($this->editOptions[$index]);
        $this->editOptions = array_values($this->editOptions);  // Reindex the array to avoid issues
    }

    public function clearEnumAttributeEdit()
    {
        $this->reset(['editName', 'editOrder', 'editDisplayText', 'editFieldType', 'editIsRequired', 'editOptionDisplayText', 'editOptionValue', 'editOptions', 'editEnumeratorAttributeModalOpen']);
        $this->resetValidation();
    }

    public function editEnumeratorAttribute()
    {
        $this->validate([
            'editOrder' => 'required',
            'editDisplayText' => 'required',
            'editFieldType' => 'required',
            'editIsRequired' => 'required',
            'editName' => [
                'required',
                function ($attribute, $value, $fail, $id) {
                    $editingAttribute = UserAttribute::find($this->editingAttributeId);
                    if ($editingAttribute->name !== $value){
                        $nameColumns = UserAttribute::where('survey_id', $this->surveyId)
                            ->pluck('name')
                            ->toArray();

                        if (in_array($value, $nameColumns)) {
                            $fail("$value column already exists!");
                        }
                    }

                },
            ],
            'editOptions.*.display_text' => 'required|string|min:1',
            'editOptions.*.value' => 'required|string|min:1',
            'editOptions' => [
                function ($attribute, $value, $fail) {
                    // Check if there are at least two options
                    if ($this->editFieldType !== 'Text' && count($value) < 2) {
                        $fail("Please provide at least two options.");
                    }
                },
            ],
        ]);

        $editingAttribute = UserAttribute::find($this->editingAttributeId);
        $editingAttribute->name = $this->editName;
        $editingAttribute->order = $this->editOrder;
        $editingAttribute->display_text = $this->editDisplayText;
        $editingAttribute->field_type = $this->editFieldType;
        $editingAttribute->is_required = $this->editIsRequired;

        // If the field type is 'Text', delete related options
        if ($this->editFieldType === 'Text') {
            $editingAttribute->options()->delete();
        } else {
            // Otherwise, update or create options
            foreach ($this->editOptions as $optionData) {
                if (isset($optionData['id'])) {
                    // Update existing option
                    $option = Option::find($optionData['id']);
                    $option->update([
                        'display_text' => $optionData['display_text'],
                        'value' => $optionData['value'],
                    ]);
                } else {
                    // Create new option
                    $editingAttribute->options()->create([
                        'display_text' => $optionData['display_text'],
                        'value' => $optionData['value'],
                    ]);
                }
            }
        }

        $editingAttribute->save();


        $this->alert('success', 'Saved changes!', [
            'customClass' => [
                'popup' => 'text-sm',
            ]
        ]);

        $this->clearEnumAttributeEdit();
    }

    public function setEnumeratorAttributeDetails($id)
    {
        $attribute = UserAttribute::find($id);
        $this->editName = $attribute->name;
        $this->editOrder = $attribute->order;
        $this->editDisplayText = $attribute->display_text;

        $this->editFieldType = $attribute->field_type;
        $this->editIsRequired = $attribute->is_required;
        $this->editOptions = $attribute->options()->get()->toArray();;
    }

    public function togglDeleteEnumeratorAttributeModal($id) {
        $this->deleteEnumeratorAttributeModalOpen = !$this->deleteEnumeratorAttributeModalOpen;
        $this->editingAttributeId = $id;
    }

    public function deleteEnumeratorAttribute($id)
    {
        $attribute = UserAttribute::find($id);

        // Delete related UserAttributeValues
        UserAttributeValue::where('user_attribute_id', $attribute->id)->delete();

        // Delete related options
        $attribute->options()->delete();

        // Delete the attribute itself
        $attribute->delete();

        // Show success alert
        $this->alert('success', 'Deleted Attribute!', [
            'customClass' => [
                'popup' => 'text-sm',
            ]
        ]);

        // Clear the deletion state
        $this->clearEnumAttributeDelete();
    }

    public function clearEnumAttributeDelete()
    {
        $this->reset(['deleteEnumeratorAttributeModalOpen']);
    }

    public function render()
    {
        $attributes = UserAttribute::where('survey_id', $this->surveyId)
            ->orderByRaw('CASE WHEN `order` = 0 THEN 1 ELSE 0 END, `order` ASC, `name` ASC')
            ->get();

        return view('livewire.pages.survey-settings.enumerator-attributes', [
            'attributes' => $attributes
        ]);
    }
}
