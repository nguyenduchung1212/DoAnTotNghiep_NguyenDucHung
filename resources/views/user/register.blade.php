<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link rel='stylesheet' href='https://use.fontawesome.com/releases/v5.2.0/css/all.css'>
    <link rel='stylesheet' href='https://use.fontawesome.com/releases/v5.2.0/css/fontawesome.css'>
    <link rel="stylesheet" href={{ asset('css/style.css') }}>
</head>

<body>
    <!-- partial:index.partial.html -->
    <div class="container">
        <div class="screen">
            <div class="screen__content">
                <form class="register" method="POST" action="{{ URL::to(route('register')) }}">
                    {!! csrf_field() !!}
                    <p class="button title"> User </p>
                    <div class="login__field">
                        <i class="login__icon fas fa-envelope"></i>
                        <input type="email" name="email" class="login__input" placeholder="Email">
                    </div>
                    <div class="login__field">
                        <i class="login__icon fas fa-user"></i>
                        <input type="text" name="name" class="login__input" placeholder="Name">
                    </div>
                    <div class="login__field">
                        <i class="login__icon fas fa-user"></i>
                        <input type="text" name="username" class="login__input" placeholder="User name">
                    </div>
                    <div class="login__field">
                        <i class="login__icon fas fa-lock"></i>
                        <input type="password" name="password" class="login__input" placeholder="Password">
                    </div>
                    <div class="login__field">
                        <i class="login__icon fas fa-lock"></i>
                        <input type="password" name="confirm_password" class="login__input"
                            placeholder="Confirm password">
                    </div>
                    <button type="submit" class="button mg-t login__submit">
                        <span class="button__text">Register</span>
                        <i class="button__icon fas fa-chevron-right"></i>
                    </button>
                    <a href="{{ URL::to(route('screen_login')) }}" class="button mg-t login__submit">
                        <span class="button__text">Login</span>
                        <i class="button__icon fas fa-chevron-left"></i>
                    </a>
                </form>
                @if (session('message'))
                    <p>{{ session('message') }}</p>
                @endif
            </div>
            <div class="screen__background">
                <span class="screen__background__shape screen__background__shape4"></span>
                <span class="screen__background__shape screen__background__shape3"></span>
                <span class="screen__background__shape screen__background__shape2"></span>
                <span class="screen__background__shape screen__background__shape1"></span>
            </div>
        </div>
    </div>
    <!-- partial -->
</body>

</html>
