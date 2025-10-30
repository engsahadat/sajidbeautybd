@props(['value' => null, 'for' => null])
<label @if($for) for="{{ $for }}" @endif {{ $attributes->merge(['class' => 'form-label fw-semibold']) }}>
    {{ $value ?? $slot }}
</label>
