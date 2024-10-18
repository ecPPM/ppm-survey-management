@props([
    'modalOpen' => false,
    'user-attributes',
    'form-data'
])

<dialog id="editEnumeratorModal" {{ $attributes->class(['modal', 'modal-open' => $modalOpen]) }}>
    <div class="modal-box flex flex-col w-11/12 max-w-3xl px-5 md:px-10 pt-0 pb-4 md:pb-8">
        <div class="flex flex-col gap-8 relative">
            <div class="flex flex-col gap-4 pt-4 md:pt-8 bg-base-100 sticky top-0 left-0">
                <h4 class="text-lg md:text-2xl font-semibold">Edit Enumerator</h4>
                <div class="h-[3px] rounded-full w-24 bg-primary"></div>
            </div>

            <form>
                <div class="">

                <x-survey.form-group id="enum_code" label="Enum Code" is_required=true>
                    <input wire:model.live="formData.enum_code" name="enum_code" type="text" aria-label="enum-code"
                           class="mb-3 input w-1/2 block {{  $errors->get('formData.enum_code')? 'input-error' : 'input-primary' }}"
                           placeholder="Enum_Code" disabled/>
                        <x-input-error :messages="$errors->get('formData.enum_code')" />
                </x-survey.form-group>

                <x-survey.form-group id="name" label="Name" is_required=true>
                    <input wire:model.live="formData.name" name="name" type="text" aria-label="name"
                           class="mb-3 input w-1/2 block {{  $errors->get('formData.name')? 'input-error' : 'input-primary' }}"
                           placeholder="Name" />
                        <x-input-error :messages="$errors->get('formData.name')" />
                </x-survey.form-group>

                <x-survey.form-group id='mode' label="Mode" is_required="true">
                    <select aria-label="select-box-for-mode"
                            name="mode"
                            id="mode"
                            wire:model.live="formData.mode"
                            class="mb-3 select {{ $errors->get('formData.mode')? 'select-error' : 'select-primary' }} w-1/2 text-base"
                    >
                        <option value="" disabled {{ empty($formData['mode']) ? 'selected' : '' }}>
                            Select Operating Mode
                        </option>
                        <option value="None">None</option>
                        <option value="Register">Register</option>
                        <option value="Deploy">Deploy</option>
                        <option value="Both">Both</option>
                    </select>
                        <x-input-error :messages="$errors->get('formData.mode')" />
                </x-survey.form-group>

                </div>

                @foreach ($userAttributes as $userAttribute)
                    @if($userAttribute->field_type == 'Text')
                        <x-survey.form-group id="{{ $userAttribute->name }}" label="{{ $userAttribute->display_text }}" is_required="{{ $userAttribute->is_required }}">
                            <input wire:model.live="formData.{{ $userAttribute->name }}" name="{{ $userAttribute->name }}" type="text" aria-label="{{ $userAttribute->name }}"
                            class="mb-3 input w-1/2 block {{ $errors->get('formData.' . $userAttribute->name) ? 'input-error' : 'input-primary' }}"
                            placeholder="Please input here." />

                            <x-input-error :messages="$errors->get('formData.' . $userAttribute->name)" />
                        </x-survey.form-group>
                    @elseif($userAttribute->field_type == 'Radio')
                        <p class="text-base-content font-medium">
                            {{ $userAttribute->display_text }}
                            @if ($userAttribute->is_required)
                                *
                            @endif
                        </p>
                        <div class="mt-1 mb-3 flex flex-col gap-1">
                            @foreach ($userAttribute->options as $option)
                                <div class="flex items-center">
                                    <label class="flex items-center cursor-pointer">
                                        <input type="radio"
                                            wire:model="formData.{{ $userAttribute->name }}"
                                            value="{{ $option->value }}"
                                            class="me-1"
                                        >
                                        {{ $option->display_text }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        <x-input-error :messages="$errors->get('formData.' . $userAttribute->name)" />
                    @else
                        <x-survey.form-group id="{{ $userAttribute->name }}" label="{{ $userAttribute->display_text }}" is_required="{{ $userAttribute->is_required }}">
                            <select aria-label="{{ $userAttribute->name }}"
                                    name="{{ $userAttribute->name }}"
                                    id="{{ $userAttribute->name }}"
                                    wire:model.live="formData.{{ $userAttribute->name }}"
                                    class="mb-3 select {{ $errors->get('formData.' . $userAttribute->name) ? 'input-error' : 'input-primary' }} w-1/2 text-base"
                            >
                            <option value="" disabled>Select {{ $userAttribute->display_text }}</option>
                            @foreach ($userAttribute->options as $option)
                                <option value="{{ $option->value }}">{{ $option->display_text }}</option>
                            @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('formData.' . $userAttribute->name)" />
                        </x-survey.form-group>
                    @endif
                @endforeach

            </form>

            <div class="flex gap-3 items-center justify-end w-full">
                <button wire:click="clearEnumeratorEdit" class="btn btn-outline">Cancel</button>
                <button wire:click="updateEnumerator" class="btn btn-primary">Update Enumerator</button>
            </div>
        </div>
    </div>
</dialog>
