@props([
    'id',
    'label',
    'class' => "",
    'is_required' => false
])

<div {{ $attributes->class(['col-span-2 md:col-span-1 flex flex-col gap-1 '.$class]) }}>
    <label for="{{$id}}" class="text-base-content font-medium">{{$label}}
        @if ($is_required)
        *
        @endif
    </label>
    {{ $slot }}
</div>
