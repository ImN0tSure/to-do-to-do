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
            <a href="#">Уведомления</a>
            <a href="#">Профиль</a>
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
                        <span class="toggle-arrow">▼</span>
                    </div>
                    <table class="task" id="taskList1" style="display: table;">
                        <thead>
                        <tr>
                            <th>Задача</th>
                            <th>Статус</th>
                            <th>Приоритет</th>
                            <th>Осталось</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($tasks as $task)
                            <tr>
                                <td>
                                    <a href="{{ route('task.show', [$task->project->url, $task->id]) }}">
                                        {{ $task->name }}
                                    </a>
                                </td>

                                @switch($task->in_progress)
                                    @case(1)
                                        <td class="status in_progress">Актуально</td>
                                        @break
                                    @default
                                        <td class="status complete">Завершено</td>
                                @endswitch

                                @switch($task->priority)
                                    @case(1)
                                        <td class="priority high">↑</td>
                                        @break
                                    @case(2)
                                        <td class="priority middle">—</td>
                                        @break
                                    @default
                                        <td class="priority low">↓</td>
                                @endswitch

                                <td class="time-left">
                                    @php
                                        echo \App\Services\HowMuchTime::expiresIn($task->end_date);
                                    @endphp
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
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
