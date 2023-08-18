@if (empty($message))
    {{ $message = '' }}
@endif
@if (empty($name))
    {{ $name = '' }}
@endif
@if (empty($lastname))
    {{ $lastname = '' }}
@endif
@if (empty($email))
    {{ $email = '' }}
@endif
@if (empty($phone))
    {{ $phone = '' }}
@endif
@if (empty($cpf))
    {{ $cpf = '' }}
@endif

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
    <title>Predict N' Score</title>
</head>

<body>
    @if ($errors->any())
        <div class="msg-alert alert alert-danger p-2 mb-3">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @elseif ($message)
        <div class="msg-alert alert alert-{{ $status == 0 ? 'danger' : 'success' }} p-2 mb-3">
            <p>{{ $message }}</p>
        </div>
    @endif
    <form class="form-login" action="{{ route('register_user') }}" method="post">
        @csrf
        <div class="login-card card">
            <div class="card-header">
                <i class="icofont-football mr-2"></i>
                <span class="font-weight-light">Predict </span>
                <span class="font-weight-bold mx-2">N'</span>
                <span class="font-weight-light">Score</span>
                <i class="icofont-brand-target ml-2"></i>
            </div>
            <div class="card-body" style="display: flex;flex-wrap: wrap;justify-content: center;">
                <div class="form-group col-lg-6">
                    <label for="name">Nome</label>
                    <input type="text" id="name" name="name" class="form-control"
                        value="{{ $name ? $name : old('name') }}" placeholder="Informe seu nome" autofocus>
                </div>
                <div class="form-group col-lg-6">
                    <label for="name">Sobrenome</label>
                    <input type="text" id="lastname" name="lastname" class="form-control"
                        value="{{ $lastname ? $lastname : old('lastname') }}" placeholder="Informe seu sobrenome"
                        autofocus>
                </div>
                <div class="form-group col-lg-6">
                    <label for="email">E-mail</label>
                    <input type="email" id="email" name="email" class="form-control"
                        value="{{ $email ? $email : old('email') }}" placeholder="Informe seu email" autofocus>
                </div>
                <div class="form-group col-lg-6">
                    <label for="emailConfirm">Confirmação de e-mail</label>
                    <input type="email" id="emailConfirm" name="emailConfirm" class="form-control"
                        placeholder="Confirme seu email" autofocus>
                </div>
                <div class="form-group col-lg-6">
                    <label for="password">Senha</label>
                    <input type="password" id="password" name="password" class="form-control"
                        placeholder="Informe sua senha">
                </div>
                <div class="form-group col-lg-6">
                    <label for="passwordConfirm">Confirmação de Senha</label>
                    <input type="password" id="passwordConfirm" name="passwordConfirm" class="form-control"
                        placeholder="Confirme sua senha">
                </div>
                <div class="form-group col-lg-6">
                    <label for="cpf">CPF</label>
                    <input type="text" id="cpf" name="cpf" class="form-control"
                        value="{{ $cpf ? $cpf : old('cpf') }}" placeholder="Informe seu cpf">
                </div>
                <div class="form-group col-lg-6">
                    <label for="phone">Celular</label>
                    <input type="text" id="phone" name="phone" class="form-control"
                        value="{{ $phone ? $phone : old('phone') }}" placeholder="Informe seu celular">
                </div>
            </div>
            <div class="card-footer">
                <input type="submit" value="Confirmar" class="btn btn-lg btn-primary mb-2">
                <a style="width: 7rem;" href="{{ route('login') }}">Voltar</a>
            </div>
        </div>
    </form>
</body>

</html>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

<script>
    window.onload = (event) => {
        $(document).ready(function() {
            setTimeout(() => {
                $('.msg-alert').addClass('d-none')
            }, 5000);
        });
    };
</script>
