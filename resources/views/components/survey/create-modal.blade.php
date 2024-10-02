@props([
    'modalOpen' => false,
])


<dialog id="newSurveyModal" {{ $attributes->class(['modal', 'modal-open' => $modalOpen]) }}>
    <div class="modal-box flex flex-col w-11/12 max-w-3xl px-5 md:px-10 pt-0 pb-4 md:pb-8">
        <div class="flex flex-col gap-8 relative">
            <div class="flex flex-col gap-4 pt-4 md:pt-8 bg-base-100 sticky top-0 left-0">
                <h4 class="text-lg md:text-2xl font-semibold">Create New Survey</h4>
                <div class="h-[3px] rounded-full w-24 bg-primary"></div>
                <button wire:click="clear" class="btn btn-sm sm:hidden z-10 btn-circle btn-ghost absolute top-2 right-0">✕</button>
            </div>

            <form class="grid grid-cols-2 gap-6">
                <x-survey.form-group id="title" label="Survey Title">
                    <input wire:model.live="title" name="title" type="text" aria-label="survey-title"
                           class="input w-full block {{  $errors->get('title')? 'input-error' : 'input-primary' }}"
                           placeholder="Enter Survey Title" />
                        <x-input-error :messages="$errors->get('title')" />
                </x-survey.form-group>

                <div class=""></div>

                <x-survey.form-group id='start-date' label="Start Date">
                    <input aria-label="start-date"
                           type="date"
                           wire:model.live="startDate"
                           class="input input-primary" />
                </x-survey.form-group>

                <x-survey.form-group id='end-date' label="End Date">
                    <input aria-label="end-date"
                           type="date"
                           wire:model.live="endDate"
                           class="input input-primary" />
                </x-survey.form-group>
            </form>

            <div class="flex gap-3 items-center justify-end w-full">
                <button wire:click="clear" class="btn btn-outline">Cancel</button>
                <button wire:click="createNewSurvey" class="btn btn-primary">Create Survey</button>
            </div>
        </div>
    </div>
</dialog>
