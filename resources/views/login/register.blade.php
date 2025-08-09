<x-layout>
    <x-slot:head_components>
        <title>
            Авторизуйтесь
        </title>
        <link rel="stylesheet" href="{{ asset('css/index.css') }}">
        <link rel="stylesheet" href="{{ asset('css/login/index.css') }}">
    </x-slot:head_components>
    <x-slot:nav>
        <nav>
            <a href="{{ route('main') }}">Главная</a>
            <a href="{{ route('login') }}">Войти</a>
        </nav>
    </x-slot:nav>
    <x-slot:main>
        <main class="main">
            <section class="hello">
                <h1>Регистрация</h1>
                <p>Заполните форму для создания аккаунта.</p>
                <form action="{{ route('user.store') }}" method="post">
                    <input type="text" class="input-field" placeholder="Имя" required>
                    <input type="email" class="input-field" placeholder="Электронная почта" required>
                    <input type="password" class="input-field" placeholder="Пароль" required>
                    <input type="password" class="input-field" placeholder="Подтвердите пароль" required>
                    <button type="submit" class="btn-primary">Зарегистрироваться</button>
                </form>
            </section>
        </main>
    </x-slot:main>
</x-layout>
