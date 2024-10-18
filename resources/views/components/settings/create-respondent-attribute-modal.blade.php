@props([
    'modalOpen' => false,
    'field-type',
    'options'
])


<dialog id="newRespondentAttributeModal" {{ $attributes->class(['modal', 'modal-open' => $modalOpen]) }}>
    <div class="modal-box flex flex-col w-11/12 max-w-3xl px-5 md:px-10 pt-0 pb-4 md:pb-8">
        <div class="flex flex-col gap-8 relative">
            <div class="flex flex-col gap-4 pt-4 md:pt-8 bg-base-100 sticky top-0 left-0">
                <h4 class="text-lg md:text-2xl font-semibold">Add New Attribute</h4>
                <div class="h-[3px] rounded-full w-24 bg-primary"></div>
            </div>

            <form class="">
                <div class="grid grid-cols-2 gap-6">
                    <x-survey.form-group id="name" label="Attribute Name">
                        <input wire:model.live="name" name="name" type="text" aria-label="attribute-name"
                               class="input w-full block {{  $errors->get('name')? 'input-error' : 'input-primary' }}"
                               placeholder="Attribute Name (Column Name)" />
                            <x-input-error :messages="$errors->get('name')" />
                    </x-survey.form-group>

                    <x-survey.form-group id='display-text' label="Display Text">
                        <input wire:model.live="displayText" name="display-text" type="text" aria-label="attribute-display-text"
                               class="input w-full block {{  $errors->get('displayText')? 'input-error' : 'input-primary' }}"
                               placeholder="Description text" />
                            <x-input-error :messages="$errors->get('displayText')" />
                    </x-survey.form-group>

                    <x-survey.form-group id='order' label="Order [Enter 0 to not display]">
                        <input wire:model.live="order" name="order" type="text" aria-label="attribute-order"
                               class="input w-full block {{  $errors->get('order')? 'input-error' : 'input-primary' }}"
                               placeholder="Display Order" />
                            <x-input-error :messages="$errors->get('order')" />
                    </x-survey.form-group>

                    <x-survey.form-group id='is-required' label="Required?">
                        <select aria-label="select-box-for-required"
                                name="is-required"
                                id="is-required"
                                wire:model.live="isRequired"
                                class="select {{ $errors->get('isRequired')? 'select-error' : 'select-primary' }} w-full text-base"
                        >
                        <option value="default" disabled>Required field? (Yes/No)</option>
                            <option value="Yes" selected>Yes</option>
                            <option value="No">No</option>
                            </select>
                            <x-input-error :messages="$errors->get('isRequired')" />
                    </x-survey.form-group>

                    <x-survey.form-group id='field-type' label="Field Type">
                        <select aria-label="select-box-for-field-type"
                                name="field-type"
                                id="field-type"
                                wire:model.live="fieldType"
                                class="select {{ $errors->get('fieldType')? 'select-error' : 'select-primary' }} w-full text-base"
                        >
                        <option value="default" disabled>Select Field Type</option>
                            <option value="Text" selected>Text Field</option>
                            <option value="Radio">Radio Buttons</option>
                            <option value="Dropdown">Dropdown</option>
                            </select>
                            <x-input-error :messages="$errors->get('fieldType')" />
                    </x-survey.form-group>
                </div>


                <div class=""></div>

                @if ($fieldType === 'Radio' || $fieldType === 'Dropdown')
                    @foreach ($options as $index => $option)
                        <div class="grid grid-cols-3 gap-6 mt-6">
                            <x-survey.form-group id="option-text-{{ $index }}" label="Option Display Text">
                                <input wire:model.live="options.{{ $index }}.displayText" name="option-text-{{ $index }}" type="text"
                                    class="input w-full block {{ $errors->get('options.'.$index.'.displayText') ? 'input-error' : 'input-primary' }}"
                                    placeholder="Display Text" />
                                <x-input-error :messages="$errors->get('options.'.$index.'.displayText')" />
                            </x-survey.form-group>

                            <x-survey.form-group id="option-value-{{ $index }}" label="Value">
                                <input wire:model.live="options.{{ $index }}.value" name="option-value-{{ $index }}" type="text"
                                    class="input w-full block {{ $errors->get('options.'.$index.'.value') ? 'input-error' : 'input-primary' }}"
                                    placeholder="Value" />
                                <x-input-error :messages="$errors->get('options.'.$index.'.value')" />
                            </x-survey.form-group>

                            <div class="flex items-center justify-center mt-auto">
                                <button wire:click.prevent="removeOption({{ $index }})" class="btn btn-error">Remove</button>
                            </div>
                        </div>
                    @endforeach

                    <div class="grid grid-cols-3 gap-6 mt-6">
                        <x-survey.form-group id="option-text" label="Option Display Text">
                            <input wire:model.live="optionDisplayText" name="option-text" type="text"
                                class="input w-full block {{ $errors->get('optionDisplayText') ? 'input-error' : 'input-primary' }}"
                                placeholder="Display Text" />
                            <x-input-error :messages="$errors->get('optionDisplayText')" />
                        </x-survey.form-group>

                        <x-survey.form-group id="option-value" label="Value">
                            <input wire:model.live="optionValue" name="option-value" type="text"
                                class="input w-full block {{ $errors->get('optionValue') ? 'input-error' : 'input-primary' }}"
                                placeholder="Value" />
                            <x-input-error :messages="$errors->get('optionValue')" />
                        </x-survey.form-group>

                        <div class="flex items-center justify-center mt-auto">
                            <button wire:click.prevent="addOption" class="btn btn-success">Save</button>
                        </div>
                    </div>
                    <x-input-error :messages="$errors->get('options')" />
                @endif
            </form>

            <div class="flex gap-3 items-center justify-end w-full">
                <button wire:click="clearRespAttributeCreate" class="btn btn-outline">Cancel</button>
                <button wire:click="saveRespondentAttribute" class="btn btn-primary">Add Attribute</button>
            </div>
        </div>
    </div>
</dialog>
