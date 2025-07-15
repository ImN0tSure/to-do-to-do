<x-layout>
    <form action="{{route('project.store')}}" method="post">
        @csrf
        <input type="text" name="name" placeholder="Название проекта"><br>
        <textarea name="description" placeholder="Описание проекта"></textarea><br>
        <input type="text" name="end_date"><br>
        <button type="submit" name="create">Создать</button>
    </form>
</x-layout>
