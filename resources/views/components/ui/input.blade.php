@props([
    'id',
    'type'         => 'text',
    'name',
    'label'        => null,
    'value'        => null,
    'autocomplete' => null,
    'placeholder'  => null,
])

<div {{ $attributes->only('class')->merge(['class' => 'mb-4']) }}>

    @if ($label || isset($hint))
        <div class="flex items-center justify-between mb-1.5">
            @if ($label)
                <label for="{{ $id }}" class="text-sm font-bold text-[#181C32]">
                    {{ $label }}
                </label>
            @endif

            @isset($hint)
                {{ $hint }}
            @endisset
        </div>
    @endif

    <input
        id="{{ $id }}"
        type="{{ $type }}"
        name="{{ $name }}"
        @if ($value)        value="{{ $value }}"               @endif
        @if ($autocomplete) autocomplete="{{ $autocomplete }}" @endif
        @if ($placeholder)  placeholder="{{ $placeholder }}"  @endif
        {{ $attributes->except(['class', 'label', 'value', 'autocomplete', 'placeholder', 'hint'])
            ->merge(['class' => 'w-full bg-gray-100 rounded-lg px-4 py-3 text-sm text-gray-800 outline-none focus:ring-2 focus:ring-blue-400 transition']) }}
    >
</div>
