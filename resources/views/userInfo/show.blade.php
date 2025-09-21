<x-layout>
    <x-slot:head_components>
        <title>
            Редактирование/создание профиля
        </title>
        <link rel="stylesheet" href="{{ asset('css/index.css') }}">
        <link rel="stylesheet" href="{{ asset('css/profile/index.css') }}">
    </x-slot:head_components>

    <x-slot:nav>
        <nav>
            <a href="{{ route('cabinet') }}">Главная</a>
            <a href="{{ route('notifications') }}">Уведомления</a>
            <a href="{{ route('user-info.edit', auth()->id()) }}">Профиль</a>
            <a href="{{ route('logout') }}">Выйти</a>
        </nav>
    </x-slot:nav>
        <nav>
            <a href="{{ route('cabinet') }}">Главная</a>
            <a href="{{ route('notifications') }}">Уведомления</a>
            <a href="{{ route('user-info.edit', auth()->id()) }}">Профиль</a>
            <a href="{{ route('logout') }}">Выйти</a>
        </nav>
    <x-slot:main>
        <main class="main">
            <section class="profile-edit">
                <h2>Профиль пользователя {{ $user->name }} {{ $user->surname }}</h2>
                <div class="avatar-wrap">
                    <div class="avatar-placeholder">
                        <img class="avatar-preview" alt="avatar" src="{{ $user->avatar_img }}">
                    </div>
                </div>
                <div class="user-info">
                    <div class="full-user-info">
                        <div>
                            <label>ФИО:</label>
                            <p>
                                {{ $user->surname }} {{ $user->name }} {{ $user->patronymic }}
                            </p>
                        </div>
                        <div>
                            <label>Контактный телефон:</label>
                            <p>{{ $user->phone }}</p>
                        </div>
                        <div>
                            <label>Email для связи:</label>
                            <p>{{ $user->contact_email }}</p>
                        </div>
                    </div>
                </div>
                <div class="about-info">
                    <label>О себе:</label>
                    <p>{{ $user->about }}</p>
                </div>
            </section>
        </main>

    </x-slot:main>

    <x-slot:scripts>

    </x-slot:scripts>

</x-layout>
