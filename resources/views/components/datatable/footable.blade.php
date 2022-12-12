@props([
    'id' => 'footable_' . uniqid()
])
<table id="{{ $id }}" {{ $attributes->merge(["class" => "table"]) }}>
    {{ $slot }}
</table>
