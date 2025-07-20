<form action="{{route('task.update', [$project_url, $task->id])}}" method="post">
    @csrf
    @method('patch')
    <p>
        Название задачи<br>
        <input type="text" name="name" placeholder="Название задачи." value="{{ $task->name }}"><br>
    </p>
    <p>
        Описание задачи<br>
        <input type="text" name="description" placeholder="Описание. Не обязательно." value="{{ $task->description }}">
        <br>
    </p>
    <p>
        Список<br>
        <select name="tasklist_id">
            @foreach ($tasklists as $tasklist)
                <option
                    value="{{ $tasklist->id }}"
                    @if($tasklist->id == $task->tasklist_id)
                        selected
                    @endif
                >
                    {{ $tasklist->name }}
                </option>
            @endforeach
        </select><br>
    </p>
    <p>
        Приоритет<br>
        <select name="priority">
            @switch($task->priority)
                @case(1)
                    <option selected value="1">Высокий</option>
                    <option value="2">Средний</option>
                    <option value="3">Низкий</option>
                    @break
                @case(2)
                    <option value="1">Высокий</option>
                    <option selected value="2">Средний</option>
                    <option value="3">Низкий</option>
                    @break
                @case(3)
                    <option value="1">Высокий</option>
                    <option value="2">Средний</option>
                    <option selected value="3">Низкий</option>
                    @break
            @endswitch
        </select>
    </p>
    <p>
        Дата и время окончания<br>
        <input name="date" type="date" value="{{ $date }}">
        <input name="time" type="time" value="{{ $time }}"><br>
    </p>
    <p>
        Статус<br>
        <select name="in_progress">
            @if($task->in_progress)
                <option selected value="1">Актуально</option>
                <option value="0">Завершено</option>
            @else
                <option value="1">Актуально</option>
                <option selected value="0">Завершено</option>
            @endif
        </select>
    </p>
    <button type="submit" name="create">Сохранить</button>
</form>
