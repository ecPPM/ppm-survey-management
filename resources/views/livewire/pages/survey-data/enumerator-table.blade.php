<div class="">
    <div class="w-full flex justify-between items-center mb-4">
        <label class="input input-bordered w-full sm:w-auto flex items-center gap-2">
            <input wire:model.live.debounce="search" type="text" class="grow border-none input-ghost"
                   placeholder="Search by Enum_Code" />
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"
                 class="w-4 h-4 opacity-70">
                <path fill-rule="evenodd"
                      d="M9.965 11.026a5 5 0 1 1 1.06-1.06l2.755 2.754a.75.75 0 1 1-1.06 1.06l-2.755-2.754ZM10.5 7a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0Z"
                      clip-rule="evenodd" />
            </svg>
        </label>
    </div>
    <div class="w-full overflow-x-scroll border">
        <table class="app-table">
            <thead>
            <tr class="text-left">
                @foreach ($columns as $column)
                    <th>{{ $column }}</th>
                @endforeach
                <th></th>
            </tr>
            </thead>

            <tbody>
                    @foreach($tableData as $row)
                        <tr>
                            @foreach($row as $cell)
                                <td>{{ $cell }}</td>
                            @endforeach
                            <td>
                                <div class="flex flex-row gap-3">
                                    <button
                                        wire:click="editEnumerator('{{ $row[0] }}')"
                                        class="btn btn-info">
                                        Edit
                                    </button>
                                    <button
                                        wire:click="deleteEnumerator('{{ $row[0] }}')"
                                        class="btn bg-red-500 text-white">
                                        Delete
                                    </button>
                                </div>

                            </td>
                        </tr>
                    @endforeach
                {{-- @foreach($attributes as $attribute)
                <tr>
                    <td>{{ $attribute->name }}</td>
                    <td>{{ $attribute->order }}</td>
                    <td>{{ $attribute->display_text }}</td>
                    <td>{{ $attribute->field_type }}</td>
                    <td>{{ $attribute->is_required == 1 ? "Yes" : "No" }}</td>
                    <td>
                        <div class="flex flex-row gap-3">
                            <button
                                wire:click=""
                                class="btn btn-info">
                                Edit
                            </button>
                            <button
                                wire:click=""
                                class="btn bg-red-500 text-white">
                                Delete
                            </button>
                        </div>

                    </td>
                </tr>
                @endforeach --}}
            </tbody>
        </table>
    </div>
    <div class="my-2">
        {{ $users->links() }}
    </div>
</div>
