<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <link rel="stylesheet" href="{{ asset('css/icofont.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/comum.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="short icon" href="{{ asset('img/icon2.ico') }}" />
    <title>Predict N' Score</title>
</head>

<body>
    @if (Session::has('success'))
        <div class="msg-alert alert alert-success">
            {{ Session::get('success') }}
        </div>
    @endif
    @if (Session::has('error'))
        <div class="msg-alert alert alert-danger">
            {{ Session::get('error') }}
        </div>
    @endif
    @if ($errors->any())
        <div class="msg-alert alert alert-danger p-2 mb-3">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif
    <form class="form-login" action="{{ route('logar') }}" method="post">
        @csrf
        <div class="login-card card">
            <div class="card-header">
                <i class="icofont-soccer mr-2"></i>
                <span class="font-weight-light">Predict </span>
                <span class="font-weight-bold mx-2">N'</span>
                <span class="font-weight-light">Score</span>
                <i class="icofont-bullseye ml-2"></i>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="email">E-mail</label>
                    <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}"
                        placeholder="Informe o e-mail" autofocus>
                </div>
                <div class="form-group">
                    <label for="password">Senha</label>
                    <div class="input-group">
                        <input type="password" id="password" name="password" class="form-control" placeholder="Informe a senha">
                        <div class="input-group-append">
                            <span id="toggle-password" class="input-group-text">
                                <i class="icofont-eye"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <input type="submit" value="Entrar" class="btn btn-lg btn-primary mb-2">
                <a style="width: 7rem;" href="{{ route('register') }}">Registre-se</a>
            </div>
        </div>
    </form>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const passwordInput = document.getElementById("password");
            const togglePasswordButton = document.getElementById("toggle-password");

            togglePasswordButton.addEventListener("click", function () {
                if (passwordInput.type === "password") {
                    passwordInput.type = "text";
                    togglePasswordButton.innerHTML = '<i class="icofont-eye-blocked"></i>';
                } else {
                    passwordInput.type = "password";
                    togglePasswordButton.innerHTML = '<i class="icofont-eye"></i>';
                }
            });
        });

    </script>
</body>

</html>
