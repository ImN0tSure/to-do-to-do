<x-layout>
    <x-slot:head_components>
        <title>
            Вывести все уведомления
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
        <main class="notes-section">
            <h2 class="your-notes">Уведомления</h2>

            <!-- Вкладки -->
            <div class="tabs-wrap">
                <div class="tabs">
                    <div class="tab" id="deadline" onclick="switchTab('deadline')">Дедлайны</div>
                    <div class="tab" id="invitation" onclick="switchTab('invitation')">Приглашения</div>
                    <div class="tab" id="other" onclick="switchTab('other')">Прочие</div>
                </div>
            </div>
            <div class="notifications-wrap">
                <!-- Вкладка 1: Дедлайны -->
                <div class="notifications deadline">
                    @if(isset($deadline))
                        @foreach($deadline as $notification)
                            @php
                                $task_name = $notification['notifiable']['name'];
                                $hours_left = $notification['event_type'];
                                $task_id = $notification['notifiable']['id'];
                                $project_url = $notification['notifiable']['project']['url'];
                                $notification_id = $notification['id'];
                            @endphp
                            <div class="notification" id="deadline_{{ $task_id }}">
                                <p>До окончания срока выполнения задачи <strong>{{ $task_name }}</strong>
                                    менее <strong>{{ $hours_left }}</strong> часов</p>
                                <div class="buttons">
                                    <a href="{{ route('task.show', [$project_url, $task_id]) }}">
                                        <button class="notes-btn">Посмотреть</button>
                                    </a>

                                    <button
                                        class="notes-btn ok-btn"
                                        data-query="delete"
                                        data-notification="{{ $notification_id }}"
                                    >Ок
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>


                <!-- Вкладка 2: Приглашения в проект -->
                <div class="notifications invitation">
                    @if(isset($invitation))
                        @foreach($invitation as $notification)
                            @switch($notification['event_type'])
                                @case('created')
                                    @php
                                        $inviter_name = $notification['notifiable']['inviter']['name'];
                                        $inviter_surname = $notification['notifiable']['inviter']['surname'];
                                        $project_name = $notification['notifiable']['project']['name'];
                                        $invitation_id = $notification['notifiable']['id'];
                                        $notification_id = $notification['id'];
                                    @endphp
                                    <div class="notification" id="invitation_{{ $invitation_id }}">
                                        <p>
                                            Пользователь <strong>{{ $inviter_name }} {{ $inviter_surname }}</strong>
                                            приглашает вас в проект <strong>{{ $project_name }}</strong>.
                                        </p>
                                        <div class="buttons">
                                            <button
                                                class="notes-btn"
                                                data-query="accept"
                                                data-invitation="{{ $invitation_id }}"
                                            >
                                                Принять
                                            </button>
                                            <button
                                                class="notes-btn"
                                                data-query="decline"
                                                data-invitation="{{ $invitation_id }}"
                                            >
                                                Отклонить
                                            </button>
                                        </div>
                                    </div>
                                    @break

                                @default
                                    @php
                                        $invitee_name = $notification['notifiable']['invitee']['name'];
                                        $invitee_surname = $notification['notifiable']['invitee']['surname'];
                                        $project_name = $notification['notifiable']['project']['name'];
                                        $invitation_id = $notification['notifiable']['id'];
                                        $reply = $notification['event_type'] === 'accepted' ? 'принял' : 'отклонил';
                                        $notification_id = $notification['id'];
                                    @endphp
                                    <div class="notification" id="invitation_{{ $invitation_id }}">
                                        <p>
                                            Пользователь <strong>{{ $invitee_name }} {{ $invitee_surname }}</strong>
                                            {{ $reply }} приглашение в проект <strong>{{ $project_name }}</strong>.
                                        </p>
                                        <div class="buttons">
                                            <button
                                                class="notes-btn ok-btn"
                                                data-query="delete"
                                                data-notification="{{ $notification_id }}"
                                            >
                                                Ок
                                            </button>
                                        </div>
                                    </div>
                                    @break
                            @endswitch
                        @endforeach
                    @endif
                </div>

                <!-- Вкладка 3: Прочие уведомления -->
                <div class="notifications other">
                    <!-- Новая задача -->
                    <div class="notification" style="display: none">
                        <p>Вам поручена задача <strong>Название задачи</strong></p>
                        <div class="buttons">
                            <button class="notes-btn">Посмотреть</button>
                            <button class="notes-btn">Ок</button>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </x-slot:main>

    <x-slot:scripts>
        <!-- Переключение вкладок -->
        <script>
            function switchTab(tab) {

                const notificationLists = document.querySelectorAll('.notifications');
                const tabList = document.querySelectorAll('.tab');

                // Прячем все списки уведомлений, убираем у всех вкладок класс active
                notificationLists.forEach(notificationList => notificationList.style.display = 'none');
                tabList.forEach(tab => tab.classList.remove('active'));

                // Показываем вкладку, у которой есть соответствующий класс
                Array.from(notificationLists).find((notificationlist) => {
                    return notificationlist.classList.contains(tab);
                }).style.display = 'block';

                // Вкладке с соответствующим id добавляем класс active
                document.getElementById(tab).classList.add('active');

            }

            // По умолчанию показываем вкладку "deadline"
            switchTab('deadline');
        </script>

        <!-- Принять/отклонить приглашение, Ок (удалить уведомление) -->
        <script>
            document.querySelector('.notifications-wrap').addEventListener('click', (event) => {
                if (event.target.tagName != 'BUTTON') return;

                const targetDataset = event.target.dataset;
                let id;
                switch (targetDataset.query) {
                    case('delete'):
                        id = targetDataset.notification;
                        removeNotification(id);
                        break;

                    case('accept'):
                        id = targetDataset.invitation;
                        acceptInvitation(id);
                        break;

                    case('decline'):
                        id = targetDataset.invitation;
                        declineInvitation(id)
                        break;

                    default:
                        return;
                }

                event.target.closest('div.notification').remove();
            });

            function acceptInvitation(id) {
                const route = '{{ route('accept-invitation') }}';
                sendInvitationResponse(id, route);
            }

            function declineInvitation(id) {
                const route = '{{ route('decline-invitation') }}';
                sendInvitationResponse(id, route);
            }

            function sendInvitationResponse(id, route) {
                fetch(route, {
                    method: "PUT",
                    headers: {
                        "Content-Type": "application/json",
                        "Accept": "application/json",
                        "X-CSRF-TOKEN": document
                            .querySelector('meta[name="csrf-token"]')
                            .getAttribute("content")
                    },
                    body: JSON.stringify({
                        notifiable_id: id
                    })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (Object.hasOwn(data, 'errors')) {
                            console.log(data);
                        } else {
                            console.log(data)
                        }
                    })
                    .catch(error => {
                        console.error("Ошибка:", error);
                    });
            }


            function removeNotification(id) {
                let route = '{{ route('notifications') }}/' + id;

                fetch(route, {
                    method: 'DELETE',
                    headers: {
                        "Content-Type": "application/json",
                        "Accept": "application/json",
                        "X-CSRF-TOKEN": document
                            .querySelector('meta[name="csrf-token"]')
                            .getAttribute("content")
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (Object.hasOwn(data, 'errors')) {
                            console.log(data);
                        } else {
                            console.log(data)
                        }
                    })
            }
        </script>
    </x-slot:scripts>
</x-layout>
