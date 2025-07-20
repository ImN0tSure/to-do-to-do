<div>
    <form method="POST" action="{{ route('tasklist.update', [$project_url, $tasklist->id]) }}">
        @csrf
        @method('patch')
        <input type="text" name="name" value="{{ $tasklist->name }}">
        <textarea name="description">
            {{ $tasklist->description }}
        </textarea>
        <button type="submit" name="update">
            Сохранить
        </button>
    </form>
</div>
