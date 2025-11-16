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
            @auth
                <a href="{{ route('cabinet') }}">Главная</a>
                <a href="{{ route('notifications') }}">Уведомления</a>
                <a href="{{ route('user-info.edit', auth()->id()) }}">Профиль</a>
                <a href="{{ route('logout') }}">Выйти</a>
            @endauth
            @guest
                <a href="{{ route('main') }}">Главная</a>
                <a href="{{ route('login') }}">Войти</a>
            @endguest
        </nav>
    </x-slot:nav>

    <x-slot:main>
        <main class="main">
            <section class="profile-edit">
                <form
                    action="
                        @auth
                        {{ route('user-info.update', auth()->id()) }}
                        @endauth
                        @guest
                        {{ route('register-user') }}
                        @endguest
                    "
                    method="POST"
                    enctype="multipart/form-data"
                >
                    @csrf
                    @auth
                        @method('PUT')
                        <h2>Редактировать профиль</h2>
                    @endauth

                    @guest
                        <h2>Заполните информацию о себе</h2>
                    @endguest

                    <!-- Поле для аватарки -->
                    <div class="avatar-wrap">
                        <label for="avatar">Аватарка</label>
                        <div class="avatar-placeholder">
                            <img
                                id="avatarPreview"
                                class="avatar-preview"
                                src="
                                    @auth
                                        {{ $user_info->avatar_img }}
                                    @endauth
                                ">
                        </div>
                        <input type="file" id="avatarUpload" name="avatar_img" accept="image/*">
                        @error('avatar_img')
                        <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Поля для имени, фамилии и отчества -->
                    <div class="user-info">
                        <div class="full-name">
                            <div class="full-name__surname">
                                <label for="surname">Фамилия*</label>
                                <input
                                    type="text"
                                    id="surname"
                                    name="surname"
                                    placeholder="Иванов"
                                    @guest
                                        value="{{ old('surname') }}"
                                    @endguest
                                    @auth
                                        value = "{{ $user_info->surname }}"
                                    @endauth
                                    required
                                >
                                @error('surname')
                                <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="full-name__name">
                                <label for="name">Имя*</label>
                                <input
                                    type="text"
                                    id="name"
                                    name="name"
                                    placeholder="Иван"
                                    @guest
                                        value="{{ old('name') }}"
                                    @endguest
                                    @auth
                                        value = "{{ $user_info->name }}"
                                    @endauth
                                    required
                                >
                                @error('name')
                                <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="full-name__patronymic">
                                <label for="patronymic">Отчество</label>
                                <input
                                    type="text"
                                    id="patronymic"
                                    name="patronymic"
                                    @guest
                                        value="{{ old('patronymic') }}"
                                    @endguest
                                    @auth
                                        value = "{{ $user_info->patronymic }}"
                                    @endauth
                                    placeholder="Иванович"
                                >
                                @error('patronymic')
                                <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="contact-info">
                            <div class="contact-info__phone">
                                <label for="phone">Контактный телефон</label>
                                <input
                                    type="text"
                                    id="phone"
                                    name="phone"
                                    @guest
                                        value="{{ old('phone') }}"
                                    @endguest
                                    @auth
                                        value = "{{ $user_info->phone }}"
                                    @endauth
                                    placeholder="+375-XX-XXX-XX-XX"
                                >
                                @error('phone')
                                <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="contact-info__email">
                                <label for="email">Email для связи*</label>
                                <input
                                    type="text"
                                    id="email"
                                    name="contact_email"
                                    @guest
                                        value="{{ old('contact_email') }}"
                                    @endguest
                                    @auth
                                        value = "{{ $user_info->contact_email }}"
                                    @endauth
                                    placeholder="work.mail@corp.com"
                                    required
                                >
                                @error('contact_email')
                                <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                    </div>

                    <!-- Поле для информации о себе -->
                    <div class="about-info">
                        <label for="about-me">О себе</label>
                        <textarea
                            id="about-me"
                            name="about"
                            placeholder="Расскажите о себе">@auth {{ $user_info->about }} @endauth</textarea>
                        @error('about')
                        <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Кнопка сохранения -->
                    <button type="submit" class="save-button">Сохранить изменения</button>
                </form>
            </section>
        </main>
    </x-slot:main>

    <x-slot:scripts>
        {{-- Предпросмотр аватарки --}}
        <script>
            const avatarPreview = document.querySelector('.avatar-preview');
            const avatarUploadBtn = document.getElementById('avatarUpload');

            avatarUploadBtn.addEventListener('change', function () {
                // Доделать логику предпросмотра аватарки через ajax
            });
        </script>
    </x-slot:scripts>

</x-layout>
