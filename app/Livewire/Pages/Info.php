<?php

namespace App\Livewire\Pages;

use App\Models\Survey;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Info extends Component
{
    public function render()
    {
        // Find the current survey
        $survey = Survey::find(Auth::user()->survey_id);

        // Retrieve the current user (assuming enumerator is the logged-in user)
        $user = User::where('id', Auth::id())->with(['attributeValues.userAttribute.options'])->first();

        // Prepare table data
        $tableData = [];

        // Fixed fields: Enum Code and Name
        $tableData[] = ['Enum Code', $user->enum_code];
        $tableData[] = ['Name', $user->name];

        // Dynamic fields: Retrieve user attributes and display their values or option display text
        foreach ($user->attributeValues as $attributeValue) {
            $attributeName = $attributeValue->userAttribute->display_text;

            // Try to get the option display text or fallback to the raw value
            $option = $attributeValue->userAttribute->options->where('value', $attributeValue->value)->first();
            $displayValue = $option ? $option->display_text : $attributeValue->value;

            // Add the attribute and its display value to the table data
            $tableData[] = [$attributeName, $displayValue];
        }

        // Pass survey and table data to the view
        return view('livewire.pages.info', [
            'survey' => $survey,
            'tableData' => $tableData,
        ]);
    }
}
