@props([
    'modalOpen' => false,
    'field-type',
    'is-required',
    'options',
])


<dialog id="editAttributeModal" {{ $attributes->class(['modal', 'modal-open' => $modalOpen]) }}>
    <div class="modal-box flex flex-col w-11/12 max-w-3xl px-5 md:px-10 pt-0 pb-4 md:pb-8">
        <div class="flex flex-col gap-8 relative">
            <div class="flex flex-col gap-4 pt-4 md:pt-8 bg-base-100 sticky top-0 left-0">
                <h4 class="text-lg md:text-2xl font-semibold">Edit Attribute</h4>
                <div class="h-[3px] rounded-full w-24 bg-primary"></div>
            </div>

            <form class="">
                <div class="grid grid-cols-2 gap-6">
                    <x-survey.form-group id="edit-name" label="Attribute Name">
                        <input wire:model.live="editName" name="edit-name" type="text" aria-label="attribute-edit-name"
                               class="input w-full block {{  $errors->get('editNname')? 'input-error' : 'input-primary' }}"
                               placeholder="Attribute Name (Column Name)" />
                            <x-input-error :messages="$errors->get('editName')" />
                    </x-survey.form-group>

                    <x-survey.form-group id='edit-display-text' label="Edit Display Text">
                        <input wire:model.live="editDisplayText" name="edit-display-text" type="text" aria-label="attribute-edit-display-text"
                               class="input w-full block {{  $errors->get('editDisplayText')? 'input-error' : 'input-primary' }}"
                               placeholder="Description text" />
                            <x-input-error :messages="$errors->get('editDisplayText')" />
                    </x-survey.form-group>

                    <x-survey.form-group id='edit-order' label="Order [Enter 0 to not display]">
                        <input wire:model.live="editOrder" name="edit-order" type="text" aria-label="attribute-edit-order"
                               class="input w-full block {{  $errors->get('editOrder')? 'input-error' : 'input-primary' }}"
                               placeholder="Display Order" />
                            <x-input-error :messages="$errors->get('editOrder')" />
                    </x-survey.form-group>

                    <x-survey.form-group id='edit-is-required' label="Required?">
                        <select aria-label="select-box-for-eidt-is-required"
                                name="edit-is-required"
                                id="edit-is-required"
                                wire:model.live="editIsRequired"
                                class="select {{ $errors->get('editIsRequired')? 'select-error' : 'select-primary' }} w-full text-base"
                        >
                            <option value="Yes" @if($isRequired) selected @endif>Yes</option>
                            <option value="No" @if($isRequired) selected @endif>No</option>
                        </select>
                        <x-input-error :messages="$errors->get('editIsRequired')" />
                    </x-survey.form-group>

                    <x-survey.form-group id='edit-field-type' label="Field Type">
                        <select aria-label="select-box-for-edit-field-type"
                                name="edit-field-type"
                                id="edit-field-type"
                                wire:model.live="editFieldType"
                                class="select {{ $errors->get('editFieldType')? 'select-error' : 'select-primary' }} w-full text-base"
                        >
                            <option value="Text" @if($fieldType === "Text") selected @endif>Text Field</option>
                            <option value="Radio" @if($fieldType === "Radio") selected @endif>Radio Buttons</option>
                            <option value="Dropdown" @if($fieldType === "Dropdown") selected @endif>Dropdown</option>
                        </select>
                        <x-input-error :messages="$errors->get('editFieldType')" />
                    </x-survey.form-group>
                </div>
                <div class=""></div>

                @if ($fieldType === 'Radio' || $fieldType === 'Dropdown')
                    @foreach ($options as $index => $option)
                        <div class="grid grid-cols-3 gap-6 mt-6">
                            <x-survey.form-group id="edit-option-text-{{ $index }}" label="Option Display Text">
                                <input wire:model.live="editOptions.{{ $index }}.display_text" name="edit-option-text-{{ $index }}" type="text"
                                    class="input w-full block {{ $errors->get('editOptions.'.$index.'.display_text') ? 'input-error' : 'input-primary' }}"
                                    placeholder="Display Text" />
                                <x-input-error :messages="$errors->get('editOptions.'.$index.'.display_text')" />
                            </x-survey.form-group>

                            <x-survey.form-group id="edit-option-value-{{ $index }}" label="Value">
                                <input wire:model.live="editOptions.{{ $index }}.value" name="edit-option-value-{{ $index }}" type="text"
                                    class="input w-full block {{ $errors->get('editOptions.'.$index.'.value') ? 'input-error' : 'input-primary' }}"
                                    placeholder="Value" />
                                <x-input-error :messages="$errors->get('editOptions.'.$index.'.value')" />
                            </x-survey.form-group>

                            <div class="flex items-center justify-center mt-auto">
                                <button wire:click.prevent="removeOption({{ $index }})" class="btn btn-error">Remove</button>
                            </div>
                        </div>
                    @endforeach

                    <div class="grid grid-cols-3 gap-6 mt-6">
                        <x-survey.form-group id="edit-option-text" label="Option Display Text">
                            <input wire:model.live="editOptionDisplayText" name="edit-option-text" type="text"
                                class="input w-full block {{ $errors->get('editOptionDisplayText') ? 'input-error' : 'input-primary' }}"
                                placeholder="Display Text" />
                            <x-input-error :messages="$errors->get('editOptionDisplayText')" />
                        </x-survey.form-group>

                        <x-survey.form-group id="edit-option-value" label="Value">
                            <input wire:model.live="editOptionValue" name="edit-option-value" type="text"
                                class="input w-full block {{ $errors->get('editOptionValue') ? 'input-error' : 'input-primary' }}"
                                placeholder="Value" />
                            <x-input-error :messages="$errors->get('editOptionValue')" />
                        </x-survey.form-group>

                        <div class="flex items-center justify-center mt-auto">
                            <button wire:click.prevent="addOption" class="btn btn-success">Save</button>
                        </div>
                    </div>
                    <x-input-error :messages="$errors->get('editOptions')" />
                @endif
            </form>

            <div class="flex gap-3 items-center justify-end w-full">
                <button wire:click="clearEnumAttributeEdit" class="btn btn-outline">Cancel</button>
                <button wire:click="editEnumeratorAttribute" class="btn btn-primary">Save Changes</button>
            </div>
        </div>
    </div>
</dialog>
