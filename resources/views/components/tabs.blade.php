@props(['active'])

<div x-data="{ tab: '{{ $active }}' }" class="w-full">

    @isset($header)
        <div class="border-b border-gray-200">
            <ul class="flex flex-wrap -mb-px text-sm font-medium text-center text-gray-500">
                {{ $header }}
            </ul>
        </div>
    @endisset

    <div class="mt-6">
        {{ $slot }}
    </div>

</div>
