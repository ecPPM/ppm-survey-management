<?php

namespace App\Livewire\Pages\SurveyData;

use App\Models\Option;
use App\Models\User;
use App\Models\UserAttribute;
use Livewire\Component;
use Livewire\WithPagination;

class EnumeratorTable extends Component
{
    use WithPagination;
    public $survey_id;
    public $search = "";

    protected $listeners = ['refreshTable' => 'refreshTable'];

    public function mount($id)
    {
        $this->survey_id = $id;
    }

    public function editEnumerator($enumCode)
    {
        $this->dispatch('toggleEditEnumeratorModal', $enumCode);
    }

    public function deleteEnumerator($enumCode)
    {
        $this->dispatch('toggleDeleteEnumeratorModal', $enumCode);
    }

    public function refreshTable()
    {
        $this->resetPage();
    }

    public function render()
    {
        // Fixed columns
        $fixedColumns = ['Enum_Code', 'Name'];

        // Retrieve UserAttribute columns with order > 0
        $attributeColumns = UserAttribute::where('survey_id', $this->survey_id)
            ->where('order', '>', 0)
            ->orderBy('order', 'ASC')
            ->orderBy('name', 'ASC')
            ->pluck('name')
            ->toArray();

        $columns = array_merge($fixedColumns, $attributeColumns);

        // Optimize query: eager loading options with attributeValues
        $users = User::where('survey_id', $this->survey_id)->where('enum_code', 'like', "%{$this->search}%")
            ->with([
                'attributeValues.userAttribute.options',
                'attributeValues' => function ($query) {
                    $query->whereHas('userAttribute', function ($query) {
                        $query->where('survey_id', $this->survey_id);
                    });
                }
            ])
            ->paginate(10); // Pagination limit

        $tableData = [];
        foreach ($users as $user) {
            $rowData = [
                $user->enum_code,
                $user->name,
            ];

            foreach ($attributeColumns as $attributeName) {
                $value = $user->attributeValues->where('userAttribute.name', $attributeName)->first();

                if ($value) {
                    $displayValue = optional($value->userAttribute->options->where('value', $value->value)->first())->display_text;
                    $rowData[] = $displayValue ?: $value->value; // Show display_text or fallback to the value itself
                } else {
                    $rowData[] = '';
                }
            }

            $tableData[] = $rowData;
        }

        return view('livewire.pages.survey-data.enumerator-table', [
            'columns' => $columns,
            'tableData' => $tableData,
            'users' => $users, // Required for pagination links
        ]);
    }

}
