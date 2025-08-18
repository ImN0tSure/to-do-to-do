<x-layout>
    <x-slot:head_components>
        <title>
            Проект
        </title>
        <meta name="csrf-token" content="{{ csrf_token() }}">
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
                        <a href="{{ route('task.create', $current_project->url) }}">
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
                            <div class="task-header">
                                <button class="edit-btn">Редактировать</button>
                                <h2>{{ $tasklist->name }}</h2>
                                <span class="toggle-arrow"
                                      onclick="toggleTasks('taskList{{ $loop->iteration }}')">▼</span>
                            </div>
                            <div class="tasks" id="taskList{{ $loop->iteration }}" style="display: none">
                                <div class="task-row row-header">
                                    <div class="task-name">Задача
                                    </div>
                                    <div class="task-status">Статус</div>
                                    <div class="task-priority">Приоритет
                                    </div>
                                    <div class="task-left">Осталось</div>
                                </div>
                                @foreach($tasklist->tasks as $task)
                                    <a href="{{ route('task.show', [$current_project->url, $task->id]) }}">
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
                    @endforeach
                </div>
            </section>
            <!-- Модальное окно -->
            <div class="modal" style="display:none" id="addListModal">
                <div class="modal-content">
                    <h2>Новый список</h2>
                    <label for="listName">Название списка</label>
                    <input type="text" id="listName" name="name" placeholder="Введите название">
                    <div class="error-name" id="errorName">Название обязательно. Минимум 3 символа</div>

                    <label for="listDesc">Описание списка</label>
                    <textarea id="listDesc" name="description" placeholder="Введите описание"></textarea>

                    <button class="btn" id="confirmAddList">Добавить</button>
                </div>
            </div>
        </main>
    </x-slot:main>

    <x-slot:scripts>

        <!-- Открытие списков и переключение между вкладками Главная и Задачи -->
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

        <!-- Добавление списка задач -->
        <script>

            const modal = document.getElementById("addListModal");
            const addListBtn = document.querySelector(".btn.add-list");
            const confirmBtn = document.getElementById("confirmAddList");

            // Открыть модалку
            addListBtn.addEventListener("click", () => {
                modal.style.display = "flex";
            });

            // Закрыть при клике вне окна
            window.addEventListener("click", (e) => {
                if (e.target === modal) {
                    modal.style.display = "none";
                }
            });

            // Обработка кнопки "Добавить"

            document.addEventListener("DOMContentLoaded", function () {
                document.getElementById('confirmAddList').addEventListener("click", function () {
                    const name = document.getElementById("listName").value.trim();
                    const desc = document.getElementById("listDesc").value.trim();
                    if (name) {
                        fetch("{{ route('tasklist.store', $current_project) }}", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "Accept": "application/json",
                                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
                            },
                            body: JSON.stringify({
                                name: name,
                                description: desc
                            })
                        })
                            .then(response => response.json())
                            .then(data => {
                                if (Object.hasOwn(data, 'errors')) {
                                    document.getElementById('errorName').style.display = 'block';
                                } else {
                                    console.log(data);
                                    modal.style.display = "none";
                                    document.getElementById("listName").value = "";
                                    document.getElementById("listDesc").value = "";
                                    location.reload();
                                }

                            })
                            .catch(error => {
                                console.error("Ошибка:", error);
                            });
                    } else {
                        alert("Введите название списка!");
                    }
                })
            });
        </script>
    </x-slot:scripts>
</x-layout>
