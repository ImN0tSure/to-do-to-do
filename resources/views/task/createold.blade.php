<form action="{{route('task.store', $project[0]->url)}}" method="post">
    @csrf
    <p>
        Название задачи<br>
        <input type="text" name="name" placeholder="Название задачи."><br>
    </p>
    <p>
        Описание задачи<br>
        <input type="text" name="description" placeholder="Описание. Не обязательно."><br>
    </p>
    <p>
        Список<br>
        <select name="tasklist_id">
            @foreach ($project[0]->tasklists as $tasklist)
                <option value="{{ $tasklist->id }}">
                    {{ $tasklist->name }}
                </option>
            @endforeach
        </select><br>
    </p>
    <p>
        Приоритет<br>
        <select name="priority">
            <option value="1">Высокий</option>
            <option selected value="2">Средний</option>
            <option value="3">Низкий</option>
        </select>
    </p>
    <p>
        Дата и время окончания<br>
        <input name="date" type="date">
        <input name="time" type="time"><br>
    </p>
    <button type="submit" name="create">Создать</button>
</form>
