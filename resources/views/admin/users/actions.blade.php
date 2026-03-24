<div class="flex items-center space-x-2">
    <x-button href="{{ route('admin.users.edit', $user) }}" blue xs>
        <i class="fa-solid fa-pen-to-square"></i>
    </x-button>

    @if($user->id !== 1)
        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="delete-form">
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
