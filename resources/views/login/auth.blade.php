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
            <a href="{{ route('user.create') }}">Зарегистрироваться</a>
        </nav>
    </x-slot:nav>
    <x-slot:main>
        <main class="main">
            <section class="hello">
                <h1>Вход</h1>
                <p>Введите электронную почту и пароль.</p>
                <form action="{{ route('authorize') }}" method="post">
                    @csrf
                    <input type="email" class="input-field" placeholder="Электронная почта" required>
                    <input type="password" class="input-field" placeholder="Пароль" required>
                    <button type="submit" class="btn-primary">Войти</button>
                </form>
            </section>
        </main>
    </x-slot:main>
</x-layout>

