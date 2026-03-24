<div class="flex items-center space-x-2">
    <x-button href="{{ route('admin.patients.edit', $patient) }}" blue xs>
        <i class="fa-solid fa-pen-to-square"></i>
    </x-button>

    @if($patient->user_id !== 1)
        <form action="{{ route('admin.patients.destroy', $patient) }}" method="POST" class="delete-form">
            @csrf
            @method('DELETE')
            <x-button type="submit" red xs>
                <i class="fa-solid fa-trash"></i>
            </x-button>
        </form>
    @else
        <x-button type="button" red xs disabled>
            <i class="fa-solid fa-trash"></i>
        </x-button>
    @endif
</div>
