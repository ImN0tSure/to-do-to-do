<x-layout>
    <x-slot:head_components>
        <title>
            Личный кабинет
        </title>
        <link rel="stylesheet" href="{{ asset('css/index.css') }}">
        <link rel="stylesheet" href="{{ asset('css/cabinet/index.css') }}">
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
        <main class="cabinet">
            <aside class="sidebar">
                <h2>Проекты</h2>
                <ul class="project-list" id="projectList">
                    @foreach($projects as $project)
                        <a href="{{ route('project.show', [$project->url]) }}">
                            <li>{{ $project->name }}</li>
                        </a>
                    @endforeach
                </ul>
                <a class="add-project-btn" href="{{ route('project.create') }}">
                    <span style="font-size: 1.2em;">＋</span>
                </a>
            </aside>

            <section class="content">

                <div class="task-list">
                    <div class="task-header" onclick="toggleTasks('taskList1')">
                        <h2>Задачи на сегодня</h2>
                        <span class="toggle-arrow">▲</span>
                    </div>
                    <div class="tasks" id="taskList1" style="display: table;">
                        <div class="task-row row-header">
                            <div class="task-name">Задача
                            </div>
                            <div class="task-status">Статус</div>
                            <div class="task-priority">Приоритет
                            </div>
                            <div class="task-left">Осталось</div>
                        </div>

                        @foreach($tasks as $task)
                            <a href="{{ route('task.show', [$task->project->url, $task->id]) }}">
                                <div class="task task-row row-elem">
                                    <div class="task-name">{{ $task->name }}</div>
                                    @switch($task->in_progress)
                                        @case(1)
                                            <div class="task-status in_progress">Актуально</div>
                                            @break
                                        @default
                                            <div class="task-status complete">Завершено</div>
                                    @endswitch

                                    @switch($task->priority)
                                        @case(1)
                                            <div class="task-priority high">↑</div>
                                            @break
                                        @case(2)
                                            <div class="task-priority middle">—</div>
                                            @break
                                        @default
                                            <div class="task-priority low">↓</div>
                                    @endswitch

                                    <div class="task-left">
                                        @php
                                            echo \App\Services\HowMuchTime::expiresIn($task->end_date);
                                        @endphp
                                    </div>
                                </div>
                            </a>
                        @endforeach

                    </div>

                </div>
            </section>
        </main>
    </x-slot:main>
    <x-slot:scripts>
        <script>
            // Функция для переключения видимости списка задач
            function toggleTasks(taskListId) {
                const taskList = document.getElementById(taskListId);
                const arrow = taskList.previousElementSibling.querySelector('.toggle-arrow');

                if (taskList.style.display === 'none' || taskList.style.display === '') {
                    taskList.style.display = 'table';
                    arrow.textContent = '▲'; // Стрелка вниз
                } else {
                    taskList.style.display = 'none';
                    arrow.textContent = '▼'; // Стрелка вверх
                }
            }
        </script>
    </x-slot:scripts>
</x-layout>
