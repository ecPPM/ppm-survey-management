@props([
    'modalOpen' => false,
    'respondent'
])


<dialog id="deleteRespondentModal" {{ $attributes->class(['modal', 'modal-open' => $modalOpen]) }}>
    <div class="modal-box flex flex-col w-11/12 max-w-3xl px-5 md:px-10 pt-0 pb-4 md:pb-8">
        <div class="flex flex-col gap-8 relative">
            <div class="flex flex-col gap-4 pt-4 md:pt-8 bg-base-100 sticky top-0 left-0">
                <h4 class="text-lg md:text-2xl font-semibold">Delete Respondent?</h4>
                <div class="h-[3px] rounded-full w-24 bg-primary"></div>
            </div>

            <div class="">
                <p>This will permanently delete this respondent and their associated data. Proceed?</p>
            </div>

            <div class="flex gap-3 items-center justify-end w-full">
                <button wire:click="clearRespondentDelete" class="btn btn-outline">Cancel</button>
                @if ($respondent)
                <button wire:click="deleteRespondent('{{ $respondent->id }}')" class="btn bg-red-500 text-base-100">Delete</button>
                @endif
            </div>
        </div>
    </div>
</dialog>
