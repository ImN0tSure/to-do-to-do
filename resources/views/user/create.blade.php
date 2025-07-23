<form action="{{ route('user.store') }}" method="POST">
    @csrf
    <p>
        Email<br>
        <input type="email" name="email">
    </p>
    <p>
        password<br>
        <input type="password" name="password">
    </p>
    <p>
        confirm password<br>
        <input type="password" name="confirm_password">
    </p>
    <button type="submit">Зарегистрироваться</button>
</form>
