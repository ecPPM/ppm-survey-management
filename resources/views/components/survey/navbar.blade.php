@props([
    'survey'
])
<nav class="mx-auto bg-white  border-b border-gray-100">
    <div class="px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-survey.nav-link
                        :href="route('surveys.settings', ['id' => $survey->id])"
                        :active="request()->routeIs('surveys.settings')"
                        wire:navigate>
                        {{ __('Settings') }}
                    </x-survey.nav-link>
                    <x-survey.nav-link
                        :href="route('surveys.enumerators', ['id' => $survey->id])"
                        :active="request()->routeIs('surveys.enumerators')"
                        wire:navigate>
                        {{ __('Survey Data') }}
                    </x-survey.nav-link>
                    <x-survey.nav-link
                        :href="route('surveys.dashboard', ['id' => $survey->id])"
                        :active="request()->routeIs('surveys.dashboard')"
                        wire:navigate>
                        {{ __('Dashboard') }}
                    </x-survey.nav-link>
                    <x-survey.nav-link
                        :href="route('surveys.questionnaire', ['id' => $survey->id])"
                        :active="request()->routeIs('surveys.questionnaire')"
                        wire:navigate>
                        {{ __('Questionnaire') }}
                    </x-survey.nav-link>
                </div>
            </div>
        </div>
    </div>
</nav>
</div>
