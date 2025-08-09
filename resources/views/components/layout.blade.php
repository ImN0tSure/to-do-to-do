<!DOCTYPE html>
<html>
<head>
    {{ $head_components }}
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
<header>
    <div class="logo">To-do to-do</div>
    <div class="clock">12:34</div> <!-- Статичное время для макета -->
    {{ $nav }}
</header>

{{ $main }}

<footer>
    © 2025 To-do to-do — Все права защищены?
</footer>
<script>
    function updateClock() {
        const clockDiv = document.querySelector('.clock');
        const now = new Date();
        // Форматируем часы, минуты и секунды с ведущими нулями
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');


        clockDiv.textContent = `${hours}:${minutes}`;
    }

    // Запускаем обновление сразу и каждую секунду
    updateClock();
    setInterval(updateClock, 1000);
</script>
</body>
</html>
