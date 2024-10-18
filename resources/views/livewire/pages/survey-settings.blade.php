<div class="bg-white max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">

        @if (session()->has('success'))
            <div class="alert alert-success text-center">
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-6 w-6 shrink-0 stroke-current"
                    fill="none"
                    viewBox="0 0 24 24">
                    <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-error text-center">
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-6 w-6 shrink-0 stroke-current"
                    fill="none"
                    viewBox="0 0 24 24">
                    <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <x-settings.edit-modal
            wire:key="edit-modal"
            :modal-open="$modalEditOpen"
            :status="$status"
        />

        <x-settings.create-enumerator-attribute-modal
            wire:key="create-enumerator-attribute-modal"
            :modal-open="$createEnumeratorAttributeModalOpen"
            :field-type="$fieldType"
            :options="$options"
        />

        <x-settings.create-respondent-attribute-modal
            wire:key="create-respondent-attribute-modal"
            :modal-open="$createRespondentAttributeModalOpen"
            :field-type="$fieldType"
            :options="$options"
        />

        <div class="card-body border rounded-md sm:flex-row mb-5 bg-gray-50">
            <div class="w-1/2 my-auto">
                <h2 class="card-title">{{ $survey->title }}</h2>
                <div class="flex flex-row my-2">
                    <div class="h-auto my-auto w-[12px]">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M152 24c0-13.3-10.7-24-24-24s-24 10.7-24 24l0 40L64 64C28.7 64 0 92.7 0 128l0 16 0 48L0 448c0 35.3 28.7 64 64 64l320 0c35.3 0 64-28.7 64-64l0-256 0-48 0-16c0-35.3-28.7-64-64-64l-40 0 0-40c0-13.3-10.7-24-24-24s-24 10.7-24 24l0 40L152 64l0-40zM48 192l352 0 0 256c0 8.8-7.2 16-16 16L64 464c-8.8 0-16-7.2-16-16l0-256z"/></svg>
                    </div>
                    <div class="ps-2">
                        <p>{{ \Carbon\Carbon::parse($survey->start_date)->format('M d, Y') }} </p>
                    </div>

                </div>
                <div class="flex flex-row">
                    <div class="h-auto my-auto w-[12px]">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M128 0c13.3 0 24 10.7 24 24l0 40 144 0 0-40c0-13.3 10.7-24 24-24s24 10.7 24 24l0 40 40 0c35.3 0 64 28.7 64 64l0 16 0 48 0 256c0 35.3-28.7 64-64 64L64 512c-35.3 0-64-28.7-64-64L0 192l0-48 0-16C0 92.7 28.7 64 64 64l40 0 0-40c0-13.3 10.7-24 24-24zM400 192L48 192l0 256c0 8.8 7.2 16 16 16l320 0c8.8 0 16-7.2 16-16l0-256zM329 297L217 409c-9.4 9.4-24.6 9.4-33.9 0l-64-64c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0l47 47 95-95c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9z"/></svg>
                    </div>
                    <div class="ps-2">
                        <p>{{ \Carbon\Carbon::parse($survey->end_date)->format('M d, Y') }} </p>
                    </div>

                </div>
            </div>
            <div class="w-1/2 flex my-auto">
                <ul class="timeline timeline-vertical">
                    <li>
                        <div class="timeline-end {{ $survey->status === 'Preparation' ? 'text-black' : 'text-gray-300' }}">Preparation</div>
                        <div class="timeline-middle">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 20 20"
                            fill="currentColor"
                            class="text-primary h-5 w-5">
                            <path
                            fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z"
                            clip-rule="evenodd" />
                        </svg>
                        </div>
                        <hr class="{{ $survey->status !== 'Preparation' ? 'bg-primary' : 'bg-gray-300' }}" />
                    </li>
                    <li>
                        <hr class="{{ $survey->status !== 'Preparation' ? 'bg-primary' : 'bg-gray-300' }}" />
                        <div class="timeline-middle">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 20 20"
                            fill="currentColor"
                            class="h-5 w-5 {{ $survey->status !== 'Preparation' ? 'text-primary' : 'text-gray-300' }}">
                            <path
                            fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z"
                            clip-rule="evenodd" />
                        </svg>
                        </div>
                        <div class="timeline-end {{ $survey->status === 'Registration' ? 'text-black' : 'text-gray-300' }}">Registration</div>
                        <hr class="{{ $survey->status === 'Over' || $survey->status === 'Deployment' ? 'bg-primary' : 'bg-gray-300' }}" />
                    </li>
                    <li>
                        <hr class="{{ $survey->status === 'Over' || $survey->status === 'Deployment' ? 'bg-primary' : 'bg-gray-300' }}" />

                        <div class="timeline-end {{ $survey->status === 'Deployment' ? 'text-black' : 'text-gray-300' }}">Deployment</div>
                        <div class="timeline-middle">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 20 20"
                            fill="currentColor"
                            class="h-5 w-5 {{ $survey->status === 'Over' || $survey->status === 'Deployment' ? 'text-primary' : 'text-gray-300' }}">
                            <path
                            fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z"
                            clip-rule="evenodd" />
                        </svg>
                        </div>
                        <hr class="{{ $survey->status === 'Over' ? 'bg-primary' : 'bg-gray-300' }}">
                    </li>
                    <li>
                        <hr class="{{ $survey->status === 'Over' ? 'bg-primary' : 'bg-gray-300' }}">
                        <div class="timeline-end {{ $survey->status === 'Over' ? 'text-black' : 'text-gray-300' }}">Over</div>
                        <div class="timeline-middle">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 20 20"
                            fill="currentColor"
                            class="h-5 w-5 {{ $survey->status === 'Over' ? 'text-primary' : 'text-gray-300' }}">
                            <path
                            fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z"
                            clip-rule="evenodd" />
                        </svg>
                        </div>
                    </li>
                    </ul>

            </div>

            <button
            wire:click="toggleEditModal"
            class="btn btn-info my-auto">
            Edit Details
            </button>
        </div>

        <div class="card-body border rounded-md bg-gray-50 mb-5">
            <div class="flex flex-row justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-3">
                    Enumerator Attributes
                </h2>

                <button
                    wire:click="toggleCreateEnumeratorAttributeModal"
                    class="btn btn-primary my-auto">
                    New Attribute
                </button>
            </div>


            @livewire('pages.survey-settings.enumerator-attributes', ['id' => $survey->id])

            <h2 class="font-semibold text-xl text-gray-800 leading-tight my-3">
                Enumerator Data
            </h2>
            <div class="flex flex-row my-3">

                <a
                    href="{{ route('download.enumerator.template', ['id' => $survey->id]) }}"
                    class="btn btn-primary">
                    Download Enumerator Template
                </a>

                <div class="tooltip tooltip-right my-auto ms-2" data-tip="Do not make changes to any column names in this template!">
                    <div class="w-5 h-5"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M464 256A208 208 0 1 0 48 256a208 208 0 1 0 416 0zM0 256a256 256 0 1 1 512 0A256 256 0 1 1 0 256zm169.8-90.7c7.9-22.3 29.1-37.3 52.8-37.3l58.3 0c34.9 0 63.1 28.3 63.1 63.1c0 22.6-12.1 43.5-31.7 54.8L280 264.4c-.2 13-10.9 23.6-24 23.6c-13.3 0-24-10.7-24-24l0-13.5c0-8.6 4.6-16.5 12.1-20.8l44.3-25.4c4.7-2.7 7.6-7.7 7.6-13.1c0-8.4-6.8-15.1-15.1-15.1l-58.3 0c-3.4 0-6.4 2.1-7.5 5.3l-.4 1.2c-4.4 12.5-18.2 19-30.6 14.6s-19-18.2-14.6-30.6l.4-1.2zM224 352a32 32 0 1 1 64 0 32 32 0 1 1 -64 0z"/></svg></div>

                </div>
            </div>

            <form action="{{ route('upload.enumerator', ['id' => $survey->id]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="my-3">
                    <h2 class="font-semibold text-l text-gray-800 leading-tight">
                        Upload Enumerator File
                    </h2>

                    <div class="flex flex-row gap-3">
                        <input id="file" name="file" type="file" class="file-input file-input-bordered w-full max-w-xs my-3"/>
                        <button type="submit" class="btn btn-primary my-auto">Upload File</button>
                    </div>
                    @error('file') <span class="error text-red-500">{{ $message }}</span> @enderror
                </div>
            </form>
        </div>

        <div class="card-body border rounded-md bg-gray-50">
            <div class="flex flex-row justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-3">
                    Respondent Attributes
                </h2>

                <button
                    wire:click="toggleCreateRespondentAttributeModal"
                    class="btn btn-primary my-auto">
                    New Attribute
                </button>
            </div>

            @livewire('pages.survey-settings.respondent-attributes', ['id' => $survey->id])

        </div>
</div>
