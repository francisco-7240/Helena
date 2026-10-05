@props(['href' => null])
@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => 'inline-flex items-center rounded-md bg-stone-900 px-4 py-2 text-sm font-semibold text-white hover:bg-rose-700']) }}>{{ $slot }}</a>
@else
    <button {{ $attributes->merge(['class' => 'inline-flex items-center rounded-md bg-stone-900 px-4 py-2 text-sm font-semibold text-white hover:bg-rose-700']) }}>{{ $slot }}</button>
@endif
