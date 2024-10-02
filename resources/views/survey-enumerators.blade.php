<x-survey.layout
    :survey="$survey"
>
    @livewire('pages.enumerators', ['survey' => $survey])
</x-survey.layout>
