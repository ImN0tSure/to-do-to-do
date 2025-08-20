<x-layout>
    <x-slot:head_components>
        <title>
            Добро пожаловать в планировщик задач To-Do to-do
        </title>
        <link rel="stylesheet" href="{{ asset('css/index.css') }}">
        <link rel="stylesheet" href="{{ asset('css/main/index.css') }}">
    </x-slot:head_components>
    <x-slot:nav>
        <nav>
            <a href="{{ route('login') }}">Войти</a>
            <a href="{{ route('user.create') }}">Зарегистрироваться</a>
        </nav>
    </x-slot:nav>
    <x-slot:main>
        <main class="main">
            <section class="hello">
                <div class="panther-icon"></div>
                <h1>Управляй задачами легко</h1>
                <p>To-do to-do — помощник в планировании и контроле задач и проектов.</p>
                <a href="{{ route('login') }}">
                    <button class="btn-primary">Начать</button>
                </a>
            </section>
        </main>
    </x-slot:main>
</x-layout>
