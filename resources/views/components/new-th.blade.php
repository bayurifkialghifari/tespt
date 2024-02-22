@props(['text', 'field' => '', 'onclick' => ''])

<th style="cursor: pointer" {{ $attributes }} x-on:click="changeOrder('{{ $field }}', order)">
    <span x-show="sort == '{{ $field }}'">
        <i x-show="order == 'asc'" class="fas fa-chevron-down"></i>
        <i x-show="order == 'desc'" class="fas fa-chevron-up"></i>
    </span>
    {{ $text }}
</th>

