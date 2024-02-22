@props([
    'edit' => true,
    'delete' => true,
])
<td>
    @if($edit)
        <button class="btn btn-warning" x-on:click="update(value.id)" {{ $attributes }}>
            <i class="fas fa-edit" ></i>
        </button>
    @endif
    @if($delete)
        <button class="btn btn-danger" x-on:click="confirmDelete(value.id)" {{ $attributes }}>
            <i class="fas fa-trash"></i>
        </button>
    @endif
</td>
