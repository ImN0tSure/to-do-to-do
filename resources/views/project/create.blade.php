<x-layout>
    <x-slot:head_components>
        <title>
            Создание проекта.
        </title>
        <link rel="stylesheet" href="{{ asset('css/index.css') }}">
        <link rel="stylesheet" href="{{ asset('css/create/index.css') }}">
    </x-slot:head_components>

    <x-slot:nav>
        <nav>
            <a href="{{ route('cabinet') }}">Главная</a>
            <a href="#">Уведомления</a>
            <a href="#">Профиль</a>
        </nav>
    </x-slot:nav>
    <x-slot:main>
        <main class="create">
            <section class="project-create">
                <h2>Создать новый проект</h2>
                <form action="{{ route('project.store') }}" method="POST">
                    @csrf
                    <div>
                        <label for="name">Название проекта</label>
                        <input type="text" id="name" name="name" placeholder="Введите название проекта" required>
                    </div>

                    <div>
                        <label for="descr">Описание проекта</label>
                        <textarea id="descr" name="description" placeholder="Введите описание проекта"
                                  required></textarea>
                    </div>

                    <div>
                        <label for="end">Дата окончания</label>
                        <input type="date" id="end" name="end_date">
                    </div>
                    <button type="submit" class="save-button">Создать проект</button>
                </form>
            </section>
        </main>
    </x-slot:main>
</x-layout>









