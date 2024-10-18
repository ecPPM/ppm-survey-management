<div class="bg-white max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <x-respondent.create-respondent-modal
            wire:key="create-respondent-modal"
            :modal-open="$createRespondentModalOpen"
            :resp-attributes="$respAttributes"
            :form-data="$formData"
    />

    <x-respondent.edit-respondent-modal
            wire:key="edit-respondent-modal"
            :modal-open="$editRespondentModalOpen"
            :resp-attributes="$respAttributes"
            :form-data="$formData"
    />

    <x-respondent.delete-respondent-modal
        wire:key="delete-respondent-modal"
        :modal-open="$deleteRespondentModalOpen"
        :respondent="$respondent"
    />

    <div class="card-body border rounded-md bg-gray-50 mb-5">
        Quota table will be here
    </div>

    <div class="card-body border rounded-md bg-gray-50 mb-5">
        <div class="flex items-end justify-end">
            <button
                wire:click="toggleCreateRespondentModal"
                class="btn btn-primary my-auto">
                New Respondent
            </button>
        </div>

        <div class="mt-3">
            @foreach($respondents as $respondent)
                <div class="flex justify-between border-b p-4 bg-gray-100 my-3">
                    <!-- Left side: Display respondent's attribute values -->
                    <div class="w-3/4">
                        <div class="mb-2">
                            <p class="font-bold text-xl">{{ $respondent->name }}</p>
                        </div>
                        <div class="mb-2">
                            <p>PWD: {{ $respondent->password }}</p>
                        </div>
                        @foreach($respondent->attributeValues as $attributeValue)
                            <div class="mb-2 text-sm">
                                @php
                                    // Check if the attribute has options, and show display text if available
                                    $displayText = $attributeValue->value; // Default to the value

                                    if ($attributeValue->respondentAttribute && $attributeValue->respondentAttribute->options) {
                                        $option = $attributeValue->respondentAttribute->options
                                            ->where('value', $attributeValue->value)
                                            ->first();

                                        if ($option) {
                                            $displayText = $option->display_text; // Override with option display text if found
                                        }
                                    }
                                @endphp
                                <p>{{ $displayText }}</p>
                            </div>
                        @endforeach
                    </div>

                    <!-- Right side: Edit and Delete buttons for each respondent -->
                    <div class="w-1/4 flex flex-col items-end justify-center space-y-2">
                        <button class="btn btn-info font-bold w-full" wire:click="toggleEditRespondentModal({{ $respondent->id }})">Edit</button>
                        <button class="btn bg-red-500 text-white w-full" wire:click="toggleDeleteRespondentModal({{ $respondent->id }})">Delete</button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
