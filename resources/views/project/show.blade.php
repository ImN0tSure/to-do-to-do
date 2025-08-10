<x-layout>
    <x-slot:head_components>
        <title>
            Проект
        </title>
        <link rel="stylesheet" href="{{ asset('css/index.css') }}">
        <link rel="stylesheet" href="{{ asset('css/cabinet/index.css') }}">
    </x-slot:head_components>

    <x-slot:nav>
        <nav>
            <a href="{{ route('cabinet') }}">Главная</a>
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
                            @if($project->url != $current_project->url)
                                <li>{{ $project->name }}</li>
                            @else
                                <li class="active">{{ $project->name }}</li>
                            @endif
                        </a>
                    @endforeach
                </ul>
                <a class="add-project-btn" href="{{ route('project.create') }}">
                    <span style="font-size: 1.2em;">＋</span>
                </a>
            </aside>

            <section class="content">
                <!-- Вкладки -->
                <div class="tabs-wrapper">
                    <div class="tabs">
                        <div class="tab" onclick="switchTab('tab1')">Главная</div>
                        <div class="tab active" onclick="switchTab('tab2')">Задачи</div>
                    </div>

                    <div class="tabs-buttons">
                        <a href="#">
                            <button class="btn add-list">Добавить список</button>
                        </a>
                        <a href="#">
                            <button class="btn add-task">Добавить задачу</button>
                        </a>
                    </div>
                </div>

                <!-- Контент вкладки -->
                <div class="tab-content" id="tab1">
                    <!-- Раздел "О проекте" -->
                    <section>
                        <h2>О проекте</h2>
                        <p>
                            {{ $current_project->description }}
                        </p>
                    </section>

                    <!-- Раздел "Участники проекта" -->
                    <section>
                        <h2>Участники проекта</h2>
                        <ul>
                            @foreach($participants as $participant)
                                <li>
                                    <div class="participant">
                                        <div class="participant-photo"
                                             style="width: 50px; height: 50px; background-color: #f06292; border-radius: 50%;"></div>
                                        <div class="participant-info">
                                            <p><strong>{{ $participant->name }} {{ $participant->surname }}</strong></p>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </section>
                </div>

                <div class="tab-content active" id="tab2">
                    <!-- Содержимое задач -->
                    @foreach($tasklists as $tasklist)
                        <div class="task-list">
                            <div class="task-header" onclick="toggleTasks('taskList{{ $loop->iteration }}')">
                                <h2>{{ $tasklist->name }}</h2>
                                <span class="toggle-arrow">▼</span>
                            </div>
                            <table class="task" id="taskList{{ $loop->iteration }}" style="display: none;">
                                <thead>
                                <tr>
                                    <th>Задача</th>
                                    <th>Статус</th>
                                    <th>Приоритет</th>
                                    <th>Осталось</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($tasklist->tasks as $task)
                                    <tr>
                                        <td>{{ $task->name }}</td>

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
                    @endforeach
                </div>
            </section>
        </main>
    </x-slot:main>

    <x-slot:scripts>
        <script>
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

            function switchTab(tabId) {
                const tabs = document.querySelectorAll('.tab');
                const tabContents = document.querySelectorAll('.tab-content');

                tabs.forEach(tab => {
                    tab.classList.remove('active');
                });

                tabContents.forEach(content => {
                    content.classList.remove('active');
                });

                document.querySelector(`#${tabId}`).classList.add('active');
                document.querySelector(`.tab[onclick="switchTab('${tabId}')"]`).classList.add('active');
            }
        </script>
    </x-slot:scripts>
</x-layout>
