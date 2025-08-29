Дедлайны<br>
<ul>
    @foreach($deadline as $event)
        <li>
            До истечения срока выполнения задачи {{ $event['notifiable']['name'] }} осталось меньше {{ $event['event_type'] }} часов.
        </li>
    @endforeach
</ul>

Приглашения<br>
<ul>
    @foreach($invitation as $event)
        <li>
            Пользователь {{ $event['notifiable']['inviter']['name'] }} {{ $event['notifiable']['inviter']['surname'] }} приглашает вас в проект {{ $event['notifiable']['project']['name'] }}
        </li>
    @endforeach
</ul>

