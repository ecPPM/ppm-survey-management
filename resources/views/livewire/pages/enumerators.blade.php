<div class="bg-white max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <x-survey-data.create-enumerator-modal
            wire:key="create-enumerator-modal"
            :modal-open="$createEnumeratorModalOpen"
            :user-attributes="$userAttributes"
            :form-data="$formData"
    />

    <x-survey-data.edit-enumerator-modal
            wire:key="edit-enumerator-modal"
            :modal-open="$editEnumeratorModalOpen"
            :user-attributes="$userAttributes"
            :form-data="$formData"
    />

    <x-survey-data.delete-enumerator-modal
        wire:key="delete-enumerator-modal"
        :modal-open="$deleteEnumeratorModalOpen"
        :enum-code="$enumCode"
    />


    <div class="card-body border rounded-md mb-5 bg-gray-50">
        <div class="flex flex-row justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-3">
                Enumerators
            </h2>

            <button
                wire:click="toggleCreateEnumeratorModal"
                class="btn btn-primary my-auto">
                New Enumerator
            </button>
        </div>

        @livewire('pages.survey-data.enumerator-table', ['id' => $survey->id])
    </div>
</div>
