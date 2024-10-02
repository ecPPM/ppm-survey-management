<?php

namespace App\Http\Controllers;

use App\Exports\EnumeratorTemplateExport;
use App\Imports\EnumeratorImport;
use App\Imports\MultiSheetImport;
use App\Models\Survey;
use App\Models\User;
use App\Models\UserAttribute;
use App\Models\UserAttributeValue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;

class SurveyController extends Controller
{
    public function index()
    {
        return view('surveys');
    }

    public function show($id)
    {
        return redirect()->route('surveys.settings', ['id' => $id]);
    }

    public function settings($id)
    {
        $survey = Survey::find($id);
        return view('survey-settings', ['survey' => $survey]);
    }

    public function upload($id, Request $request)
    {
        // Validate the uploaded file (optional)
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:4096',  // Adjust MIME types and size as necessary
        ]);

        // Get the uploaded file
        $file = $request->file('file');

        // Read the file contents
        $data = Excel::toArray(new EnumeratorImport, $file); // Use the EnumeratorImport class

        // Assuming the first sheet contains the headers and data
        $headers = $data[0][0]; // Get the first row (header row)
        $rows = array_slice($data[0], 1); // Get the data rows, skipping the header row

        // Define the required columns ('Enum_Code' and 'Name')
        $requiredColumns = ['Enum_Code', 'Name'];

        // Get the list of expected columns and their 'is_required' status from the UserAttribute model
        $userAttributes = UserAttribute::where('survey_id', $id)
            ->get(['name', 'is_required']) // Retrieve both name and is_required fields
            ->keyBy('name'); // Key the collection by 'name' for easier access

        // Check if the uploaded file contains all required columns and expected UserAttribute columns
        $uploadedColumns = array_diff($headers, $requiredColumns); // Remove required columns from uploaded headers
        $expectedColumns = $userAttributes->keys()->toArray();

        if (array_diff($uploadedColumns, $expectedColumns) || count($uploadedColumns) !== count($expectedColumns)) {
            return redirect()->back()->withErrors(['file' => 'The uploaded file columns do not match the expected columns.']);
        }

        // Combine required columns ('Enum_Code', 'Name') with UserAttribute columns that are marked as required
        $allRequiredColumns = collect($requiredColumns)
            ->merge($userAttributes->filter(function ($attribute) {
                return $attribute->is_required; // Filter only columns marked as required
            })->keys())
            ->toArray();

        // Validate each row's data
        foreach ($rows as $rowIndex => $row) {
            foreach ($allRequiredColumns as $requiredColumn) {
                // Get the index of the required column in the header row
                $columnIndex = array_search($requiredColumn, $headers);

                if ($columnIndex !== false) {
                    $cellValue = $row[$columnIndex]; // Get the cell value for the required column in the current row

                    // Check if the cell is empty for required columns
                    if (empty($cellValue)) {
                        return redirect()->back()->withErrors([
                            'file' => "Row " . ($rowIndex + 2) . " contains an empty value for required column '{$requiredColumn}'."
                        ]);
                    }
                }
            }
        }


        // Get the list of user attributes with their 'id' and 'name'
        $userAttributes = UserAttribute::where('survey_id', $id)
            ->get(['id', 'name'])
            ->keyBy('name'); // Key by the 'name' for easier access

        DB::beginTransaction(); // Start a database transaction

        try {
            foreach ($rows as $row) {
                // Get 'Enum_Code' and 'Name' from the row
                $enumCode = $row[array_search('Enum_Code', $headers)];
                $name = $row[array_search('Name', $headers)];

                // Check if the user with this 'Enum_Code' already exists
                $user = User::where('enum_code', $enumCode)->first();

                if ($user) {
                    // If the user exists but belongs to a different survey, return an error
                    if ($user->survey_id != $id) {
                        // Rollback and return error if the user is associated with a different survey
                        DB::rollBack();
                        return redirect()->back()->withErrors([
                            'file' => "Enum_Code '{$enumCode}' already exists in another survey! Uploaded data was not processed."
                        ]);
                    }

                    // Existing user - Update user data
                    $user->update(['name' => $name]);

                    // Update user_attribute_values for this user
                    foreach ($userAttributes as $attributeName => $attribute) {
                        $columnIndex = array_search($attributeName, $headers); // Find the index of the column
                        $value = $row[$columnIndex]; // Get the cell value for this attribute

                        if (empty($value)) {
                            // If empty, delete this entry
                            UserAttributeValue::where('user_id', $user->id)
                                                ->where('user_attribute_id', $attribute->id)
                                                ->delete();
                        } else {
                            // Update or create user_attribute_value
                        UserAttributeValue::updateOrCreate(
                            ['user_id' => $user->id, 'user_attribute_id' => $attribute->id],
                            ['value' => $value]
                        );
                        }

                    }
                } else {
                    // New user - Create a new record in 'users' table
                    $user = User::create([
                        'enum_code' => $enumCode,
                        'name' => $name,
                        'role_id' => 2,
                        'password' => Hash::make('password'),
                        'survey_id' => $id,
                        'mode' => 'None'
                    ]);

                    // Insert user_attribute_values for this new user
                    foreach ($userAttributes as $attributeName => $attribute) {
                        $columnIndex = array_search($attributeName, $headers); // Find the index of the column
                        $value = $row[$columnIndex]; // Get the cell value for this attribute

                        // Only create a new user_attribute_value if the value is not empty or the attribute is not required
                        if (!$attribute->is_required && empty($value)) {
                            continue;
                        }

                        UserAttributeValue::updateOrCreate(
                            ['user_id' => $user->id, 'user_attribute_id' => $attribute->id],
                            ['value' => $value]
                        );
                    }
                }
            }

            DB::commit(); // Commit the transaction
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback if there's an error
            return redirect()->back()->withErrors(['file' => 'An error occurred while processing the file: ' . $e->getMessage()]);
        }


        // Redirect back with success message
        return redirect()->back()->with('success', 'Enumerator list uploaded successfully! Please check in the "Survey Data" Menu.');
    }

    public function checkFormatErrors($id, $file)
    {
        Excel::import(new MultiSheetImport($id), $file);
    }

    public function downloadEnumeratorTemplate($id)
    {
        return Excel::download(new EnumeratorTemplateExport($id), 'Enumerator_template.xlsx');
    }

    public function enumerators($id)
    {
        $survey = Survey::find($id);
        return view('survey-enumerators', ['survey' => $survey]);
    }

    public function respondents($id)
    {
        $survey = Survey::find($id);
        return view('survey-respondents', ['survey' => $survey]);
    }

    public function dashboard($id)
    {
        $survey = Survey::find($id);
        return view('dashboard', ['survey' => $survey]);
    }

    public function questionnaire($id)
    {
        $survey = Survey::find($id);
        return view('questionnaire', ['survey' => $survey]);
    }
}
