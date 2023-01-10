<span>
    <a class="btn btn-info" href="{{ route('user_edit', ['id' => $user->id]) }}">
        <i class="bi bi-pencil-square"></i>
    </a>
    <a class="btn btn-info" href="{{ route('user_show', ['id' => $user->id]) }}">
        <i class="bi bi-eye"></i>
    </a>
</span>
