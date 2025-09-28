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
            <a href="{{ route('notifications') }}">Уведомления</a>
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
                        <label for="taskTitle">Заголовок</label>
                        <div id="taskTitle">{{ $task->name }}</div>
                    </div>

                    <!-- Поле для краткого описания задачи -->
                    <div class="form-field">
                        <label for="taskDescription">Краткое описание задачи</label>
                        <div id="taskDescription">{{ $task->description }}</div>
                    </div>

                    <!-- Поле для выбора исполнителя -->
                    <div class="form-field">
                        <label for="executor">Исполнитель</label>
                        @php
                            $current_user_id = auth()->id();
                        @endphp

                        @switch($task->executor)
                            @case(null)
                                <select id="executor" name="executor_id" style="max-width: 20%">
                                    <option selected value=""></option>
                                    <option value="{{ auth()->id() }}">
                                        {{ $current_user->name }} {{ $current_user->surname }}
                                    </option>
                                </select>
                                @break
                            @default
                                @if($task->executor->id === auth()->id())
                                    <select id="executor" name="executor_id" style="max-width: 20%">
                                        <option value=""></option>
                                        <option selected value="{{ auth()->id() }}">
                                            {{ $current_user->name }} {{ $current_user->surname }}
                                        </option>
                                    </select>
                                @else
                                    <div id="executor">{{ $task->executor->name }} {{ $task->executor->surname }}</div>
                                @endif
                        @endswitch

                        @error('executor_id')
                        <div class="error-message">Исполнителя не существует.</div>
                        @enderror
                    </div>

                    <!-- Поле для выбора списка -->
                    <div class="form-field">
                        <label for="tasklist">Список</label>
                        @if($task->executor === null || $current_user->user_id === $task->executor?->id)
                            <select id="tasklist" name="tasklist_id" style="max-width: 20%" required>
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
                        @else
                            <div id="tasklist">
                                {{ $tasklists[$task->tasklist->id - 1]->name }}
                            </div>
                        @endif
                    </div>

                    <!-- Поле для даты окончания -->
                    <div class="form-field" style="width: 20%">
                        <label for="end_date">Дата окончания</label>
                        <div id="end_date">
                            {{ \Carbon\Carbon::parse($task->end_date)->format('Y-m-d') }}
                        </div>
                    </div>

                    <!-- Поле для время окончания -->
                    <div class="form-field" style="width: 20%">
                        <label for="end_time">Время окончания</label>
                        <div id="end_time">
                            {{ \Carbon\Carbon::parse($task->end_date)->format('H:i') }}
                        </div>
                    </div>

                    <!-- Поле приоритета -->
                    <div class="form-field">
                        <label for="priority">Приоритет</label>
                        <div id="priority">
                            @switch($task->priority)
                                @case(1)
                                    Высокий
                                    @break
                                @case(2)
                                    Средний
                                    @break
                                @case(3)
                                    Низкий
                                    @break
                            @endswitch
                        </div>
                    </div>

                    <label for="in_progress">Статус</label>
                    <div id="in_progress">
                        @if($task->in_progress)
                            Актуально
                        @else
                            Завершено
                        @endif
                    </div>
                </form>
                @if($task->executor === null || $task->executor?->id === auth()->id())
                    <!-- Кнопки сохранения и удаления -->
                    <div class="button-wrapper">
                        <button type="submit" form="taskEditForm" class="button save-button">Сохранить</button>
                    </div>
                @endif
            </section>
        </main>
    </x-slot:main>
</x-layout>
