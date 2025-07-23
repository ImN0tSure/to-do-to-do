<div>
    <form method="post" action="{{ route('user.update', $user->id) }}">
        @method('patch')
        @csrf
        <p>
            Старый пароль<br>
            <input type="password" name="password">
        </p>
        <p>
            Новый пароль<br>
            <input type="password" name="new_password">
        </p>
        <p>
            Подтвердить новый пароль<br>
            <input type="password" name="confirm_new_password">
        </p>
        <button type="submit">Сменить пароль</button>
    </form>
</div>
