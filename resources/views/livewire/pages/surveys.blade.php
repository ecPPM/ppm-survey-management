<div class="">
    <div class="bg-white mx-auto py-6 px-4 flex-col lg:flex-row sm:px-6 lg:px-8 max-w-7xl lg:navbar">
        <div class="lg:navbar-start">
            <select id="status" wire:model.change="selectedStatus" class="select select-bordered lg:w-1/2 w-full bg-gray-100 text-black">
                <option value="">Filter By Status</option>
                @foreach ($statuses as $status)
                    <option value="{{ $status }}">{{ $status }}</option>
                @endforeach
            </select>
        </div>

        <div class="lg:navbar-center">
            <div class="w-full flex justify-between items-center">
                <label class="input input-bordered bg-gray-100 w-full sm:w-auto flex items-center gap-2">
                    <input wire:model.live.debounce="search" id="search" type="text" class="grow border-none input-ghost"
                           placeholder="Search By Title" />
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"
                         class="w-4 h-4 opacity-70">
                        <path fill-rule="evenodd"
                              d="M9.965 11.026a5 5 0 1 1 1.06-1.06l2.755 2.754a.75.75 0 1 1-1.06 1.06l-2.755-2.754ZM10.5 7a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0Z"
                              clip-rule="evenodd" />
                    </svg>
                </label>
            </div>
        </div>

        <div class="lg:navbar-end">
            <button class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded">
                New Survey
            </button>
        </div>
    </div>
    <div class="py-6 text-black">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            @foreach ($surveys as $survey)
                <x-survey-list-item
                    :survey="$survey"
                />
            @endforeach

            <div class="my-2">
                {{ $surveys->links() }}
            </div>
        </div>
    </div>
</div>



