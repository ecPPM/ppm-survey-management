@props([
    'modalOpen' => false,
    'status'
])


<dialog id="editSurveyModal" {{ $attributes->class(['modal', 'modal-open' => $modalOpen]) }}>
    <div class="modal-box flex flex-col w-11/12 max-w-3xl px-5 md:px-10 pt-0 pb-4 md:pb-8">
        <div class="flex flex-col gap-8 relative">
            <div class="flex flex-col gap-4 pt-4 md:pt-8 bg-base-100 sticky top-0 left-0">
                <h4 class="text-lg md:text-2xl font-semibold">Edit Survey Details</h4>
                <div class="h-[3px] rounded-full w-24 bg-primary"></div>
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

                <x-survey.form-group id="status" label="Survey Status">
                    <select aria-label="select-box-for-survey-status"
                            name="status"
                            id="status"
                            wire:model.live="status"
                            class="select {{ $errors->get('status')? 'select-error' : 'select-primary' }} w-full text-base"
                    >
                        <option value="Preparation" @if ($status === "Preparation")
                            selected
                        @endif>Preparation</option>
                        <option value="Registration" @if ($status === "Registration")
                            selected
                        @endif>Registration</option>
                        <option value="Deployment" @if ($status === "Deployment")
                            selected
                        @endif>Deployment</option>
                        <option value="Over" @if ($status === "Over")
                            selected
                        @endif>Over</option>
                    </select>
                    <x-input-error :messages="$errors->get('status')" />
                </x-survey.form-group>
            </form>

            <div class="flex gap-3 items-center justify-end w-full">
                <button wire:click="clearSurveyEdit" class="btn btn-outline">Cancel</button>
                <button wire:click="editSurvey" class="btn btn-primary">Save Changes</button>
            </div>
        </div>
    </div>
</dialog>
