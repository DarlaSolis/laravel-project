{{-- resources/views/components/actions.blade.php --}}
@props([
    'editRoute',
    'deleteRoute',
    'itemId',
    'itemName',
    'hideDelete' => false
])

<div class="flex items-center space-x-2">
    {{-- Botón Editar --}}
    <x-button href="{{ $editRoute }}" blue xs>
        <i class="fa-solid fa-pen-to-square"></i>
    </x-button>

    {{-- Botón Eliminar --}}
    @unless($hideDelete)
        {{-- Formulario oculto para eliminar --}}
        <form id="delete-form-{{ $itemId }}"
              action="{{ $deleteRoute }}"
              method="POST"
              style="display: none;">
            @csrf
            @method('DELETE')
        </form>

        {{-- Botón que dispara el SweetAlert --}}
        <x-button
            type="button"
            red
            xs
            onclick="confirmDelete({{ $itemId }}, '{{ $itemName }}')"
        >
            <i class="fa-solid fa-trash"></i>
        </x-button>
    @else
        {{-- Botón deshabilitado --}}
        <x-button type="button" red xs disabled>
            <i class="fa-solid fa-trash"></i>
        </x-button>
    @endunless
</div>
