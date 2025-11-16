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
                <form action="{{ route('tmp-save-user') }}" method="post">
                    @csrf
                    <div class="input-wrap">
                        <input
                            type="email"
                            class="input-field"
                            placeholder="Электронная почта"
                            name="email"
                            value="{{ old('email') }}"
                            required
                        >
                        @error('email')
                        <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="input-wrap">
                        <input type="password" class="input-field" placeholder="Пароль" name="password" required>
                        @error('password')
                        <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="input-wrap">
                        <input
                            type="password"
                            class="input-field"
                            placeholder="Подтвердите пароль"
                            name="confirm_password"
                            required
                        >
                        @error('confirm_password')
                        <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn-primary">Зарегистрироваться</button>
                </form>
            </section>
        </main>
    </x-slot:main>
</x-layout>
