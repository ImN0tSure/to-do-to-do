<form action="{{route('tasklist.store', $project_url)}}" method="post">
    @csrf
    <input type="text" name="name" placeholder="Название списка."><br>
    <input type="text" name="description" placeholder="Описание. Не обязательно.">
    <button type="submit" name="create">Создать</button>
</form>
@if($errors->tasklist->any())
    <div>
        <p>Ошибка в названии</p>
    </div>
@endif

