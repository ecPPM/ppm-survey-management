<?php

namespace App\Exports;

use App\Models\UserAttribute;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class EnumeratorTemplateExport implements FromArray, WithHeadings
{
    protected $surveyId;

    // Constructor to accept the survey ID
    public function __construct($surveyId)
    {
        $this->surveyId = $surveyId;
    }

    // This function defines the headers for the Excel file
    public function headings(): array
    {
        // Fetching the UserAttributes for the given survey
        $attributeColumns = UserAttribute::where('survey_id', $this->surveyId)
            ->orderByRaw("CASE WHEN `order` = 0 THEN 1 ELSE 0 END, `order` ASC")
            ->pluck('name')
            ->toArray();

        return array_merge(['Enum_Code', 'Name'], $attributeColumns);
    }

    // If you want to add any specific data to the rows, use this method
    public function array(): array
    {
        // For now, returning an empty array since it's just a template
        return [];
    }
}
