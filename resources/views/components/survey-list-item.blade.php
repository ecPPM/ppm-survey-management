
@props([
    'survey',
])

<div wire:key={{ $survey->id }} class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <a href="{{ url('/surveys/' . $survey->id) }}" class="card card-compact flex w-full hover:bg-gray-200 transition duration-200">
        <div class="card-body sm:flex-row">
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
                {{-- <progress class="progress progress-info w-56" value="70" max="100"></progress> --}}

                {{-- @php
                    $statusColor = match($survey->status) {
                        'Preparation' => 'bg-yellow-500',
                        'Registration' => 'bg-blue-500',
                        'Deployment' => 'bg-green-500',
                        'Over' => 'bg-gray-500',
                        default => 'bg-gray-200', // Fallback color if status doesn't match
                    };
                @endphp --}}

                {{-- <div class="mt-[2px] w-4 h-4 {{ $statusColor }} rounded-full">
                </div>
                <div class="ms-2"><p>{{ $survey->status }}</p></div> --}}

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
        </div>
    </a>

</div>
