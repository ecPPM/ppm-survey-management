@props([
    'survey',
])

<x-app-layout>
    <div class="bg-gray-100 max-w-7xl mx-auto">
        <div class="max-w-7xl bg-white mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __($survey->title) }}
            </h2>
        </div>
        <x-survey.navbar
            :survey="$survey"
        />

        {{ $slot }}

    </div>

    {{-- @livewire('pages.surveys-list') --}}

</x-app-layout>

