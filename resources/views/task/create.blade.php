<x-layout>
    <x-slot:head_components>
        <title>
            Создать задачу
        </title>
        <link rel="stylesheet" href="{{ asset('css/index.css') }}">
        <link rel="stylesheet" href="{{ asset('css/create/index.css') }}">
    </x-slot:head_components>

    <x-slot:nav>
        <nav>
            <a href="{{ route('cabinet') }}">Главная</a>
            <a href="{{ route('notifications') }}">Уведомления</a>
            <a href="{{ route('user-info.edit', auth()->id()) }}">Профиль</a>
            <a href="{{ route('logout') }}">Выйти</a>
        </nav>
    </x-slot:nav>

    <x-slot:main>
        <main class="create">
            <section class="task-create">
                <h2>Новая задача</h2>
                <form action="{{ route('task.store', $project->url) }}" method="POST">
                    @csrf
                    <!-- Поле для заголовка задачи -->
                    <div class="form-field">
                        <label for="taskTitle">Заголовок задачи</label>
                        <input
                            type="text"
                            id="taskTitle"
                            name="name"
                            placeholder="Введите заголовок задачи"
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
                        ></textarea>
                        @error('description')
                        <div class="error-message">Описание обязательно. Максимум 1500 символов.</div>
                        @enderror
                    </div>

                    <!-- Поле для выбора исполнителя -->
                    <div class="form-field">
                        <label for="executor">Исполнитель</label>
                        <select id="executor" name="executor_id">
                            <option value="" selected></option>
                            @foreach($project->participants as $participant)
                                <option value="{{ $participant->id }}">
                                    {{ $participant->name }} {{ $participant->suename }}
                                </option>
                            @endforeach
                        </select>
                        @error('executor_id')
                        <div class="error-message">Исполнителя не существует.</div>
                        @enderror
                    </div>

                    <!-- Поле для выбора списка -->
                    <div class="form-field">
                        <label for="tasklist">Список</label>
                        <select id="tasklist" name="tasklist_id" required>
                            <option selected></option>
                            @foreach($project->tasklists as $tasklist)
                                <option value="{{ $tasklist->id }}">
                                    {{ $tasklist->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('tasklist_id')
                        <div class="error-message">Список не принадлежит проекту.</div>
                        @enderror
                    </div>

                    <!-- Поле для даты окончания -->
                    <div class="form-field">
                        <label for="end_date">Дата окончания</label>
                        <input type="date" id="end_date" name="end_date" required style="width: 20%">
                        @error('end_date')
                        <div class="error-message">Введите корректную дату.</div>
                        @enderror
                    </div>

                    <!-- Поле для время окончания -->
                    <div class="form-field">
                        <label for="end_time">Время окончания</label>
                        <input type="time" id="end_time" name="end_time" required style="width: 20%">
                        @error('end_time')
                        <div class="error-message">Введите корректное время.</div>
                        @enderror
                    </div>

                    <!-- Поле приоритета -->
                    <div class="form-field">
                        <label for="priority">Приоритет</label>
                        <select id="priority" name="priority" required>
                            <option value="1">Высокий</option>
                            <option value="2">Средний</option>
                            <option value="3">Низкий</option>
                        </select>
                        @error('priority')
                        <div class="error-message">Некорректно выбран приоритет.</div>
                        @enderror
                    </div>

                    <!-- Кнопка сохранения -->
                    <button type="submit" class="button save-button">Сохранить</button>
                </form>
            </section>
        </main>
    </x-slot:main>

</x-layout>
