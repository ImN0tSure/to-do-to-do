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
                <div class="tabs-wrap">
                    <div class="tabs">
                        <div class="tab" onclick="switchTab('tab1')">Главная</div>
                        <div class="tab active" onclick="switchTab('tab2')">Задачи</div>
                    </div>

                    <div class="tabs-buttons">
                        <button class="btn add-list">Добавить список</button>
                        <a href="{{ route('task.create', $current_project->url) }}">
                            <button class="btn add-task">Добавить задачу</button>
                        </a>
                    </div>
                </div>

                <!-- Контент вкладки -->
                <div class="tab-content" id="tab1">
                    <!-- Раздел "О проекте" -->
                    <div class="section">
                        <h2>О проекте</h2>
                        <p>
                            {{ $current_project->description }}
                        </p>
                    </div>

                    <!-- Раздел "Участники проекта" -->
                    <div class="section">
                        <h2>Участники проекта</h2>
                        <div class="project-participants-wrap">
                            @foreach($participants as $participant)
                                <div class="participant" data-user-id="{{ $participant->user_id }}">
                                    <div class="participant-photo">
                                        <img src="{{ $participant->avatar_img }}" alt="avatar-img"/>
                                    </div>
                                    <div class="participant-info">
                                        <div class="participant-name">
                                            <strong>
                                                {{ $participant->name }} {{ $participant->surname }}
                                                @if(auth()->id() === $participant->user_id)
                                                    (Вы)
                                                @endif
                                            </strong>
                                        </div>
                                        <div class="participant-status">
                                            <span>
                                                @switch($participant->pivot->status)
                                                    @case('0')
                                                        создатель
                                                        @break
                                                    @case('1')
                                                        куратор
                                                        @break
                                                    @default()
                                                        исполнитель
                                                @endswitch
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <!-- Раздел "Дополнительные кнопки -->
                    <div class="section additional-btn">
                        <button class="btn" onclick="openAddParticipantModal()">Добавить участника</button>
                        <button class="btn" onclick="openExcludeParticipantsModal()">Исключить участников</button>
                        <button class="btn" onclick="quitProject()">Покинуть проект</button>
                    </div>
                </div>

                <div class="tab-content active" id="tab2">
                    <!-- Содержимое задач -->
                    @foreach($tasklists as $tasklist)
                        <div class="task-list">
                            <div class="task-header">
                                <button
                                    class="edit-btn"
                                    onclick="editTasklist('{{ $tasklist->id }}')"
                                >Редактировать
                                </button>
                                <h2 id="tasklist{{ $tasklist->id }}Header">{{ $tasklist->name }}</h2>
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
            <!-- Модальное окно создания списка-->
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
            <!-- Модальное окно изменения списка-->
            <div class="modal" style="display:none" id="editListModal">
                <div class="modal-content">
                    <h2>Изменить название списка</h2>
                    <label for="newlistName">Название списка</label>
                    <input type="text" id="newlistName" name="newName" placeholder="Введите название">
                    <input type="text" id="oldlistName" name="oldName" hidden>
                    <div class="error-name" id="editErrorName">Название обязательно. Минимум 3 символа</div>

                    <button class="btn" id="confirmEditList">Сохранить</button>
                    <button class="btn delete-list" id="deleteList">X Удалить список</button>
                </div>
            </div>
            <!-- Модальное окно добавления участника -->
            <div class="modal" style="display:none" id="addParticipantModal">
                <div class="modal-content">
                    <h2>Добавление участника в проект</h2>
                    <label for="participantName">Email пользователя</label>
                    <input type="email" id="participantEmail" name="email" placeholder="Введите email">
                    <div class="error-name" id="addParticipantError">Текст ошибки</div>

                    <button
                        class="btn add-participant"
                        id="addNewParticipant"
                        onclick="addParticipant()"
                    >Добавить
                    </button>
                </div>
            </div>
            <!-- Модальное окно исключения участников проекта -->
            <div class="modal" style="display:none" id="excludeParticipantsModal">
                <div class="modal-content">
                    <p class="exclude-note">Выберите участников, которых хотите исключить</p>
                    <input class="search-for-exclusion" type="text" placeholder="Поиск">
                    <div class="exclude-wrap">
                        <div class="participants-list">

                        </div>
                        <div class="exclude-btn">
                            <button class="btn exclude" onclick="excludeSelectedParticipants()">Х Исключить выбранных
                            </button>
                        </div>
                    </div>
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

            const addModal = document.getElementById("addListModal");
            const addListBtn = document.querySelector(".btn.add-list");
            const confirmBtn = document.getElementById("confirmAddList");

            // Открыть модалку
            addListBtn.addEventListener("click", () => {
                addModal.style.display = "flex";
            });

            // Закрыть при клике вне окна
            window.addEventListener("click", (e) => {
                if (e.target === addModal) {
                    addModal.style.display = "none";
                }
            });

            // Обработка кнопки "Добавить Задачу"

            document.addEventListener("DOMContentLoaded", function () {
                confirmBtn.addEventListener("click", function () {
                    const name = document.getElementById("listName").value.trim();
                    const desc = document.getElementById("listDesc").value.trim();
                    if (name) {
                        fetch("{{ route('tasklist.store', $current_project) }}", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "Accept": "application/json",
                                "X-CSRF-TOKEN": document
                                    .querySelector('meta[name="csrf-token"]')
                                    .getAttribute("content")
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
                                    addModal.style.display = "none";
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

        <!-- Редактирование списка задач и его удаление -->
        <script>

            const editModal = document.getElementById("editListModal");
            const confirmAddBtn = document.getElementById("confirmEditList");
            const deleteListBtn = document.getElementById('deleteList');
            const routeTpl = '{{ route('tasklist.update', [$current_project, '#tasklistId#']) }}';
            const newListName = document.getElementById("newlistName");
            const oldListName = document.getElementById('oldlistName');

            let route;
            let tasklistHeader;

            // Закрыть при клике вне окна и сбросить поля
            window.addEventListener("click", (e) => {
                if (e.target === editModal) {
                    editModal.style.display = "none";
                    newListName.setAttribute('value', '');
                    newListName.value = '';
                }
            });

            // Открыть и заполнить модалку
            function editTasklist(tasklistId) {

                route = routeTpl.replace('#tasklistId#', tasklistId);
                tasklistHeader = document.getElementById('tasklist' + tasklistId + 'Header');
                let currentListName = tasklistHeader.textContent;

                newListName.setAttribute('value', currentListName);
                newListName.value = currentListName;
                oldListName.setAttribute('value', currentListName);

                editModal.style.display = "flex";
            }

            // Обработать кнопку "Сохранить"
            confirmAddBtn.addEventListener("click", function () {
                if (newListName.value === oldListName.value) {
                    editModal.style.display = "none";
                } else {
                    fetch(route, {
                        method: 'PUT',
                        headers: {
                            "Content-Type": "application/json",
                            "Accept": "application/json",
                            "X-CSRF-TOKEN": document
                                .querySelector('meta[name="csrf-token"]')
                                .getAttribute("content")
                        },
                        body: JSON.stringify({
                            'name': newListName.value,
                            'oldName': oldListName.value,
                        }),
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (Object.hasOwn(data, 'errors')) {
                                document.getElementById('editErrorName').style.display = 'block';
                            } else {
                                tasklistHeader.innerHTML = data.data.newName;
                                editModal.style.display = "none";
                            }

                        })
                        .catch(error => {
                            console.error("Ошибка:", error);
                        });
                }
            })

            //Обработать кнопку "Х Удалить список"
            deleteListBtn.addEventListener("click", function () {

                let answer = confirm(
                    'Вы уверены, что хотите удалить список со всеми задачами?' +
                    ' Это действие необратимо.'
                )

                if (answer) {
                    fetch(route, {
                        method: 'DELETE',
                        headers: {
                            "Content-Type": "application/json",
                            "Accept": "application/json",
                            "X-CSRF-TOKEN": document
                                .querySelector('meta[name="csrf-token"]')
                                .getAttribute("content")
                        },
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (Object.hasOwn(data, 'errors')) {
                                console.log(data);
                            } else {
                                tasklistHeader.parentNode.parentNode.remove();
                                editModal.style.display = "none";
                            }
                        })
                        .catch(error => {
                            console.error("Ошибка:", error);
                        });
                } else {
                    editModal.style.display = "none";
                }

            })

        </script>

        <!-- Добавление нового участника -->
        <script>

            const addParticipantModal = document.getElementById('addParticipantModal');
            const participantEmailField = document.getElementById('participantEmail');
            const errorMessageDiv = document.getElementById('addParticipantError');

            // Открыть модалку.
            function openAddParticipantModal() {
                addParticipantModal.style.display = "flex";
            }

            // Очистить и закрыть модалку
            function clearAndCloseAddModal() {
                addParticipantModal.style.display = "none";
                participantEmailField.value = '';
                errorMessageDiv.style.display = 'none';
                errorMessageDiv.innerText = '';
            }

            // Закрыть модалку при клике вне её
            window.addEventListener("click", (e) => {
                if (e.target === addParticipantModal) {
                    console.log(e.target);
                    clearAndCloseAddModal();
                }
            });

            // Добавление пользователя
            function addParticipant() {

                const participantEmail = participantEmailField.value.trim();

                if (participantEmail) {
                    fetch('{{ route('invite-participant') }}', {
                        method: 'POST',
                        headers: {
                            "Content-Type": "application/json",
                            "Accept": "application/json",
                            "X-CSRF-TOKEN": document
                                .querySelector('meta[name="csrf-token"]')
                                .getAttribute("content")
                        },
                        body: JSON.stringify({
                            email: participantEmail,
                            project_url: '{{ $current_project->url }}'
                        })
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (Object.hasOwn(data, 'errors')) {

                                let errorMessage;
                                if (data.errors.project_url) {
                                    errorMessage = data.errors.project_url[0];
                                } else if (data.errors.email) {
                                    errorMessage = data.errors.email[0];
                                }
                                errorMessageDiv.style.display = 'block';
                                errorMessageDiv.innerText = errorMessage;
                            } else {
                                console.log(data);
                                clearAndCloseAddModal();
                                alert('Уведомление успешно отправлено');
                            }

                        })
                        .catch(error => {
                            console.error("Ошибка:", error);
                        });
                }

            }
        </script>

        <!-- Покинуть проект -->
        <script>
            function quitProject() {
                if (confirm('Вы действительно хотите покинуть текущий проект?')) {
                    console.log('Yes');
                    window.location.href = '{{ route('project.quit', $current_project->url) }}';
                }
            }
        </script>

        <!-- Удалить участника -->
        <script>
            const excludeParticipantsModal = document.getElementById('excludeParticipantsModal');
            const participantsList = document.querySelector('.participants-list');
            const participantsForExclusion = [];

            participantsList.addEventListener('click', (e) => {
                let selectedParticipant = e.target.closest('.participant');

                if (selectedParticipant) {
                    const id = selectedParticipant?.dataset.userId;
                    selectedParticipant.querySelector('.participant-name').classList.toggle('excluded');
                    addOrDeleteToExclusionArray(id);
                }

            })

            function addOrDeleteToExclusionArray(id) {
                const index = participantsForExclusion.indexOf(id);

                if (index !== -1) {
                    participantsForExclusion.splice(index, 1);
                } else {
                    participantsForExclusion.push(id);
                }
            }

            function openExcludeParticipantsModal() {
                const participantsList = document.querySelector('.project-participants-wrap').innerHTML;
                document.querySelector('.participants-list').innerHTML = participantsList;

                excludeParticipantsModal.style.display = 'flex';
            }

            window.addEventListener("click", (e) => {
                if (e.target === excludeParticipantsModal) {
                    clearAndCloseExcludeModal();
                }
            });

            function clearAndCloseExcludeModal() {
                excludeParticipantsModal.style.display = 'none';
                excludeParticipantsModal.querySelector('.participants-list').innerHTML = '';
                participantsForExclusion.length = 0;
            }

            async function excludeSelectedParticipants() {
                if (participantsForExclusion.length < 1) {
                    clearAndCloseExcludeModal();
                    return;
                }

                try {
                    const response = await fetch('{{ route('project.exclude-participants', $current_project->url) }}', {
                        method: 'DELETE',
                        headers: {
                            "Content-Type": "application/json",
                            "Accept": "application/json",
                            "X-CSRF-TOKEN": document
                                .querySelector('meta[name="csrf-token"]')
                                .getAttribute("content")
                        },
                        body: JSON.stringify({
                            ids: participantsForExclusion
                        })
                    })

                    const data = await response.json();

                    for (const id in data) {
                        if (data[id].status === 'success') {
                            removeParticipantFromPage(id);
                        } else {
                            console.log(data)
                            throw new Error(data.message)
                        }
                    }

                } catch (e) {
                    console.log(e.message);
                }

                clearAndCloseExcludeModal();
            }

            function removeParticipantFromPage(id) {
                const participants = document.querySelectorAll('.participant');
                let participantsArr = Array.from(participants);

                participantsArr.forEach(function (elem) {
                    if (elem.dataset.userId === id) {
                        elem.remove();
                    }
                })
            }
        </script>

        <!-- Переход в профиль пользователя -->
        <script>
            const usersList = document.querySelector('.project-participants-wrap');

            usersList.addEventListener('click', (e)=>{
                const selectedUser = e.target.closest('.participant');
                const userId = selectedUser?.dataset.userId;
                const userRouteTpl = '{{ route('user-info.show', '#userId#') }}';
                let route;

                if (userId) {
                    route = userRouteTpl.replace('#userId#', userId);
                    window.location.href = route;
                }
            })
        </script>

    </x-slot:scripts>
</x-layout>
