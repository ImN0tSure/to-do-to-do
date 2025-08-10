<x-layout>
    <x-slot:head_components>
        <title>
            Проект
        </title>
        <link rel="stylesheet" href="{{ asset('css/index.css') }}">
        <link rel="stylesheet" href="{{ asset('css/create/index.css') }}">
    </x-slot:head_components>

    <x-slot:nav>
        <nav>
            <a href="{{ route('cabinet') }}">Главная</a>
            <a href="#">Уведомления</a>
            <a href="#">Профиль</a>
        </nav>
    </x-slot:nav>

    <x-slot:main>
        <main class="create">
            <section class="task-create">
                <h2>Задача</h2>
                <form action="{{ route('task.update', [$project_url, $task->id]) }}">
                    @csrf
                    @method('put')
                    <!-- Поле для заголовка задачи -->
                    <div>
                        <label for="taskTitle">Заголовок задачи</label>
                        <input
                            type="text"
                            id="taskTitle"
                            name="taskTitle"
                            placeholder="Введите заголовок задачи"
                            value="{{ $task->name }}"
                            required
                        >
                    </div>

                    <!-- Поле для краткого описания задачи -->
                    <div>
                        <label for="taskDescription">Краткое описание задачи</label>
                        <textarea
                            id="taskDescription"
                            name="taskDescription"
                            placeholder="Введите краткое описание задачи"
                            required
                        >
                        {{ $task->description }}
                    </textarea>
                    </div>

                    <!-- Поле для выбора исполнителя -->
                    <div>
                        <label for="executor">Исполнитель</label>
                        <select id="executor" name="executor" required>
                            @foreach($participants as $participant)
                                <option
                                    @if($task->executor->id == $participant->id)
                                        selected
                                    @endif
                                    value="{{ $participant->name }} {{ $participant->suename }}"
                                >
                                    {{ $participant->name }} {{ $participant->suename }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Поле для выбора списка -->
                    <div>
                        <label for="tasklist">Список</label>
                        <select id="tasklist" name="tasklist" required>
                            @foreach($tasklists as $tasklist)
                                <option
                                    @if($task->tasklist->id == $tasklist->id)
                                        selected
                                    @endif
                                    value="{{ $tasklist->name }}"
                                >
                                    {{ $tasklist->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Поле для даты окончания -->
                    <div>
                        <label for="end_date">Дата окончания</label>
                        <input
                            type="date"
                            id="end_date"
                            name="end_date"
                            value="{{ \Carbon\Carbon::parse($task->end_date)->format('Y-m-d') }}"
                            required
                        >

                    </div>

                    <!-- Поле для время окончания -->
                    <div>
                        <label for="end_time">Время окончания</label>
                        <input
                            type="time"
                            id="end_time"
                            name="end_time"
                            value="{{ \Carbon\Carbon::parse($task->end_date)->format('H:i:s') }}"
                            required
                        >
                    </div>

                    <!-- Поле приоритета -->
                    <div>
                        <label for="priority">Приоритет</label>
                        <select id="priority" name="priority">
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
                    </div>

                    <label for="in_progress">Статус</label>
                    <select id="in_progress" name="in_progress">
                        @if($task->in_progress)
                            <option selected value="1">Актуально</option>
                            <option value="0">Завершено</option>
                        @else
                            <option value="1">Актуально</option>
                            <option selected value="0">Завершено</option>
                        @endif
                    </select>

                    <!-- Кнопка сохранения -->
                    <button type="submit" class="save-button">Сохранить</button>
                </form>
            </section>
        </main>
    </x-slot:main>

    <x-slot:scripts>

    </x-slot:scripts>
</x-layout>
