<x-layout>
    <x-slot:head_components>
        <title>
            Просмотреть задачу.
        </title>
        <link rel="stylesheet" href="{{ asset('css/index.css') }}">
        <link rel="stylesheet" href="{{ asset('css/create/index.css') }}">
    </x-slot:head_components>

    <x-slot:nav>
        <nav>
            <a href="{{ route('cabinet') }}">Главная</a>
            <a href="#">Уведомления</a>
            <a href="{{ route('user-info.edit', auth()->id()) }}">Профиль</a>
            <a href="{{ route('logout') }}">Выйти</a>
        </nav>
    </x-slot:nav>

    <x-slot:main>
        <main class="create">
            <section class="task-create">
                <h2>Задача</h2>
                <form id="taskEditForm" action="{{ route('task.update', [$project_url, $task->id]) }}" method="POST">
                    @csrf
                    @method('put')
                    <!-- Поле для заголовка задачи -->
                    <div class="form-field">
                        <label for="taskTitle">Заголовок задачи</label>
                        <input
                            type="text"
                            id="taskTitle"
                            name="name"
                            placeholder="Введите заголовок задачи"
                            value="{{ $task->name }}"
                            required
                        >
                        @error('name')
                        <div class="error-message">Заголовок обязателен. Минимум 3 символа.</div>
                        @enderror
                    </div>

                    <!-- Поле для краткого описания задачи -->
                    <div class="form-field">
                        <label for="taskDescription">Краткое описание задачи</label>
                        <textarea
                            id="taskDescription"
                            name="description"
                            placeholder="Введите краткое описание задачи"
                            required
                        >{{ $task->description }}</textarea>
                        @error('description')
                        <div class="error-message">Описание обязательно. Максимум 1500 символов.</div>
                        @enderror
                    </div>

                    <!-- Поле для выбора исполнителя -->
                    <div class="form-field">
                        <label for="executor">Исполнитель</label>
                        <select id="executor" name="participant">

                            <option selected value=""></option>

                            @foreach($participants as $participant)
                                @switch($task->executor)
                                    @case(null):
                                    <option value="{{ $participant->id }}">
                                        {{ $participant->name }} {{ $participant->suename }}
                                    </option>
                                    @break

                                    @default
                                        <option
                                            @if($task->executor->id === $participant->id)
                                                selected
                                            @endif
                                            value="{{ $participant->id }}"
                                        >
                                            {{ $participant->name }} {{ $participant->suename }}
                                        </option>
                                @endswitch
                            @endforeach

                        </select>
                        @error('participant')
                        <div class="error-message">Исполнителя не существует.</div>
                        @enderror
                    </div>

                    <!-- Поле для выбора списка -->
                    <div class="form-field">
                        <label for="tasklist">Список</label>
                        <select id="tasklist" name="tasklist_id" required>
                            @foreach($tasklists as $tasklist)
                                <option
                                    @if($task->tasklist->id == $tasklist->id)
                                        selected
                                    @endif
                                    value="{{ $tasklist->id }}"
                                >
                                    {{ $tasklist->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('tasklist_id')
                        <div class="error-message">Список не принадлежит проекту.</div>
                        @enderror
                    </div>

                    <!-- Поле для даты окончания -->
                    <div class="form-field" style="width: 20%">
                        <label for="end_date">Дата окончания</label>
                        <input
                            type="date"
                            id="end_date"
                            name="end_date"
                            value="{{ \Carbon\Carbon::parse($task->end_date)->format('Y-m-d') }}"
                            required
                        >
                        @error('end_date')
                        <div class="error-message">Введите корректную дату.</div>
                        @enderror
                    </div>

                    <!-- Поле для время окончания -->
                    <div class="form-field" style="width: 20%">
                        <label for="end_time">Время окончания</label>
                        <input
                            type="time"
                            id="end_time"
                            name="end_time"
                            value="{{ \Carbon\Carbon::parse($task->end_date)->format('H:i') }}"
                            required
                        >
                        @error('end_time')
                        <div class="error-message">Введите корректное время.</div>
                        @enderror
                    </div>

                    <!-- Поле приоритета -->
                    <div class="form-field">
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
                        @error('priority')
                        <div class="error-message">Некорректно выбран приоритет.</div>
                        @enderror
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
                        @error('in_progress')
                        <div class="error-message">Такого статуса не существует.</div>
                        @enderror
                    </select>
                </form>

                <!-- Кнопки сохранения и удаления -->
                <div class="button-wrapper">
                    <button type="submit" form="taskEditForm" class="button save-button">Сохранить</button>
                    <form action="{{ route('task.destroy', [$project_url, $task->id]) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button
                            name="delete"
                            class="button delete-button"
                        >
                            Х Удалить задачу
                        </button>
                    </form>

                </div>
            </section>
        </main>
    </x-slot:main>

    <x-slot:scripts>
        <script>
            const deleteButton = document.querySelector('.delete-button');
            deleteButton.addEventListener('click', function (event) {
                if (!confirm("Вы уверены, что хотите удалить задачу?")) {
                    event.preventDefault();
                }
            });
        </script>
    </x-slot:scripts>
</x-layout>
