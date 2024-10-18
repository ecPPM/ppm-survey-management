@props([
    'modalOpen' => false,
    'resp-attributes',
    'form-data'
])


<dialog id="editRespondentModal" {{ $attributes->class(['modal', 'modal-open' => $modalOpen]) }}>
    <div class="modal-box flex flex-col w-11/12 max-w-3xl px-5 md:px-10 pt-0 pb-4 md:pb-8">
        <div class="flex flex-col gap-8 relative">
            <div class="flex flex-col gap-4 pt-4 md:pt-8 bg-base-100 sticky top-0 left-0">
                <h4 class="text-lg md:text-2xl font-semibold">Edit Respondent</h4>
                <div class="h-[3px] rounded-full w-24 bg-primary"></div>
            </div>

            <form>
                <div class="">

                <x-survey.form-group id="name" label="Name" is_required=true>
                    <input wire:model.live="formData.name" name="name" type="text" aria-label="name"
                           class="mb-3 input block {{  $errors->get('formData.name')? 'input-error' : 'input-primary' }}"
                           placeholder="Name" />
                        <x-input-error :messages="$errors->get('formData.name')" />
                </x-survey.form-group>

                <x-survey.form-group id="password" label="Password" is_required=true>
                    <input wire:model.live="formData.password" name="password" type="text" aria-label="password"
                           class="mb-3 input block {{  $errors->get('formData.password')? 'input-error' : 'input-primary' }}"
                           placeholder="Password" />
                        <x-input-error :messages="$errors->get('formData.password')" />
                </x-survey.form-group>

                </div>
                @foreach ($respAttributes as $respAttribute)
                    @if($respAttribute->field_type == 'Text')
                        <x-survey.form-group id="{{ $respAttribute->name }}" label="{{ $respAttribute->display_text }}" is_required="{{ $respAttribute->is_required }}">
                            <input wire:model.live="formData.{{ $respAttribute->name }}" name="{{ $respAttribute->name }}" type="text" aria-label="{{ $respAttribute->name }}"
                            class="mb-3 input block {{ $errors->get('formData.' . $respAttribute->name) ? 'input-error' : 'input-primary' }}"
                            placeholder="Please input here." />

                            <x-input-error :messages="$errors->get('formData.' . $respAttribute->name)" />
                        </x-survey.form-group>
                    @elseif($respAttribute->field_type == 'Radio')
                        <p class="text-base-content font-medium">
                            {{ $respAttribute->display_text }}
                            @if ($respAttribute->is_required)
                                *
                            @endif
                        </p>
                        <div class="mt-1 mb-3 flex flex-col gap-1">
                            @foreach ($respAttribute->options as $option)
                                <div class="flex items-center">
                                    <label class="flex items-center cursor-pointer">
                                        <input type="radio"
                                            wire:model="formData.{{ $respAttribute->name }}"
                                            value="{{ $option->value }}"
                                            class="me-1"
                                        >
                                        {{ $option->display_text }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        <x-input-error :messages="$errors->get('formData.' . $respAttribute->name)" />
                    @else
                        <x-survey.form-group id="{{ $respAttribute->name }}" label="{{ $respAttribute->display_text }}" is_required="{{ $respAttribute->is_required }}">
                            <select aria-label="{{ $respAttribute->name }}"
                                    name="{{ $respAttribute->name }}"
                                    id="{{ $respAttribute->name }}"
                                    wire:model.live="formData.{{ $respAttribute->name }}"
                                    class="mb-3 select {{ $errors->get('formData.' . $respAttribute->name) ? 'input-error' : 'input-primary' }} text-base"
                            >
                            <option value="" disabled selected>Select {{ $respAttribute->display_text }}</option>
                            @foreach ($respAttribute->options as $option)
                                <option value="{{ $option->value }}">{{ $option->display_text }}</option>
                            @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('formData.' . $respAttribute->name)" />
                        </x-survey.form-group>
                    @endif
                @endforeach

            </form>

            <div class="flex gap-3 items-center justify-end w-full">
                <button wire:click="clearRespondentEdit" class="btn btn-outline">Cancel</button>
                <button wire:click="updateRespondent" class="btn btn-primary">Update Respondent</button>
            </div>
        </div>
    </div>
</dialog>
