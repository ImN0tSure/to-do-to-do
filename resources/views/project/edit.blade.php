<div>
    <form method="POST" action="{{ route('update', $project->url) }}">
        @csrf
        @method('patch')
        <input type="text" name="name" value="{{ $project->name }}">
        <textarea name="description">
            {{ $project->description }}
        </textarea>
        <button type="submit" name="update">
            Сохранить
        </button>
    </form>
</div>
