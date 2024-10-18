<?php

namespace App\Livewire\Pages\SurveySettings;

use App\Models\Option;
use App\Models\RespondentAttribute;
use App\Models\UserAttribute;
use App\Models\UserAttributeValue;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class RespondentAttributes extends Component
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
    public $editRespondentAttributeModalOpen = false;

    // delete
    public $deleteRespondentAttributeModalOpen = false;

    public function mount($id)
    {
        $this->surveyId = $id;
    }

    public function toggleEditRespondentAttributeModal($id)
    {
        $this->editRespondentAttributeModalOpen = !$this->editRespondentAttributeModalOpen;
        $this->editingAttributeId = $id;
        if ($this->editRespondentAttributeModalOpen) {
            $this->setRespondentAttributeDetails($id);
        } else {
            $this->clearRespAttributeEdit();
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

    public function clearRespAttributeEdit()
    {
        $this->reset(['editName', 'editOrder', 'editDisplayText', 'editFieldType', 'editIsRequired', 'editOptionDisplayText', 'editOptionValue', 'editOptions', 'editRespondentAttributeModalOpen']);
        $this->resetValidation();
    }

    public function editRespondentAttribute()
    {
        $this->validate([
            'editOrder' => 'required',
            'editDisplayText' => 'required',
            'editFieldType' => 'required',
            'editIsRequired' => 'required',
            'editName' => [
                'required',
                function ($attribute, $value, $fail, $id) {
                    $editingAttribute = RespondentAttribute::find($this->editingAttributeId);
                    if ($editingAttribute->name !== $value){
                        $nameColumns = RespondentAttribute::where('survey_id', $this->surveyId)
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

        $editingAttribute = RespondentAttribute::find($this->editingAttributeId);
        $editingAttribute->name = $this->editName;
        $editingAttribute->order = $this->editOrder;
        $editingAttribute->display_text = $this->editDisplayText;
        $editingAttribute->field_type = $this->editFieldType;
        $editingAttribute->is_required = $this->editIsRequired;

        // If the field type is 'Text', delete related options
        if ($this->editFieldType === 'Text') {
            $editingAttribute->options()->delete();
        } else {
            // Get the current option IDs in the database for this attribute
            $existingOptionIds = $editingAttribute->options()->pluck('id')->toArray();

            // Collect IDs of options being edited/created (those in the form data)
            $updatedOptionIds = [];

            // Loop through the form data for options
            foreach ($this->editOptions as $optionData) {
                if (isset($optionData['id'])) {
                    // Update existing option
                    $option = Option::find($optionData['id']);
                    $option->update([
                        'display_text' => $optionData['display_text'],
                        'value' => $optionData['value'],
                    ]);
                    $updatedOptionIds[] = $optionData['id'];  // Add to the list of updated options
                } else {
                    // Create new option
                    $newOption = $editingAttribute->options()->create([
                        'display_text' => $optionData['display_text'],
                        'value' => $optionData['value'],
                    ]);
                    $updatedOptionIds[] = $newOption->id;  // Add the new option ID to the list
                }
            }

            // Determine which options have been removed (existing IDs not present in updated option IDs)
            $deletedOptionIds = array_diff($existingOptionIds, $updatedOptionIds);

            // Delete the removed options from the 'options' table
            Option::whereIn('id', $deletedOptionIds)->delete();
        }

        $editingAttribute->save();


        $this->alert('success', 'Saved changes!', [
            'customClass' => [
                'popup' => 'text-sm',
            ]
        ]);

        $this->clearRespAttributeEdit();
    }

    public function setRespondentAttributeDetails($id)
    {
        $attribute = RespondentAttribute::find($id);
        $this->editName = $attribute->name;
        $this->editOrder = $attribute->order;
        $this->editDisplayText = $attribute->display_text;

        $this->editFieldType = $attribute->field_type;
        $this->editIsRequired = $attribute->is_required;
        $this->editOptions = $attribute->options()->get()->toArray();;
    }

    public function togglDeleteRespondentAttributeModal($id) {
        $this->deleteRespondentAttributeModalOpen = !$this->deleteRespondentAttributeModalOpen;
        $this->editingAttributeId = $id;
    }

    public function deleteRespondentAttribute($id)
    {
        $attribute = RespondentAttribute::find($id);

        // Delete related UserAttributeValues
        RespondentAttribute::where('respondent_attribute_id', $attribute->id)->delete();

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
        $this->clearRespAttributeDelete();
    }

    public function clearRespAttributeDelete()
    {
        $this->reset(['deleteRespondentAttributeModalOpen']);
    }

    public function render()
    {
        $attributes = RespondentAttribute::where('survey_id', $this->surveyId)
            ->orderByRaw('CASE WHEN `order` = 0 THEN 1 ELSE 0 END, `order` ASC, `name` ASC')
            ->get();

        return view('livewire.pages.survey-settings.respondent-attributes', [
            'attributes' => $attributes
        ]);
    }
}
