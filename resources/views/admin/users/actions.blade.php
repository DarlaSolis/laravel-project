<div class="flex items-center space-x-2">
    <x-wire-button href="{{ route('admin.users.edit', $user) }}" blue xs>
        <i class="fa-solid fa-pen-to-square"></i>
    </x-wire-button>

    @if($user->id !== 1)
        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="delete-form">
            @csrf
            @method('DELETE')
            <x-wire-button type="submit" red xs>
                <i class="fa-solid fa-trash"></i>
            </x-wire-button>
        </form>
    @else
        <x-wire-button type="button" red xs disabled>
            <i class="fa-solid fa-trash"></i>
        </x-wire-button>
    @endif
</div>
