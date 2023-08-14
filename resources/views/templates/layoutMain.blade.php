<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/template.css') }}">
    <link rel="stylesheet" href="{{ asset('css/icofont.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/comum.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="short icon" href="{{ asset('img/icon2.ico') }}" />
    @yield('style')
    <title>Predict n' Score</title>
</head>

<body class="">
    <header class="header">
        <div class="logo">
            <i class="icofont-soccer mr-2"></i>
            <span class="font-weight-light">Predict </span>
            <span class="font-weight-bold mx-2">N'</span>
            <span class="font-weight-light">Score</span>
            <i class="icofont-bullseye ml-2"></i>
        </div>
        <div class="menu-toggle mx-3">
            <i class="icofont-navigation-menu"></i>
        </div>
        <div class="spacer"></div>
        <div class="dropdown">
            <div class="dropdown-button">
                <span class="ml-2">{{ session()->get('name') }}</span>
                <i class="icofont-simple-down mx-2"></i>
            </div>
            <div class="dropdown-content">
                <ul class="nav-list">
                    <li class="nav-item">
                        <a href="{{ route('logout') }}">
                            <i class="icofont-logout mx-2"></i>
                            Sair
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </header>
    <aside class="sidebar">
        <nav class="menu mt-3">
            <ul class="nav-list">
                <li class="nav-item">
                    <a href="{{ route('minhas_previsoes') }}">
                        <i class="icofont-bullseye mr-2"></i>
                        Minhas previsões
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('classificacao') }}">
                        <i class="icofont-numbered mr-2"></i>
                        Classificação
                    </a>
                </li>
                {{-- <li class="nav-item">
                    <a href="{{ route('campeonatos') }}">
                        <i class="icofont-trophy-alt mr-2"></i>
                        Campeonatos
                    </a>
                </li> --}}
                @if (session()->get('isAdmin')) 
                    <li class="nav-item">
                        <a href="{{ route('apis') }}">
                            <i class="icofont-code mr-2"></i>
                            Apis
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('users') }}">
                            <i class="icofont-users mr-2"></i>
                            Usuários
                        </a>
                    </li>
                @endif
            </ul>
        </nav>
        {{-- <div class="sidebar-widgets">
            <div class="sidebar-widget">
                <i class="icon icofont-hour-glass text-primary"></i>
                <div class="info">
                    <span class="main text-primary">
                        00:00
                    </span>
                    <span class="label text-muted">
                        Horas Trabalhadas
                    </span>
                </div>
            </div>
            <div class="division my-3"></div>
            <div class="sidebar-widget">
                <i class="icon icofont-ui-alarm text-danger"></i>
                <div class="info">
                    <span class="main text-danger">
                        00:00
                    </span>
                    <span class="label text-muted">
                        Hora de Saída
                    </span>
                </div>
            </div>
        </div> --}}
    </aside>
    @yield('conteudo')
    <footer class="footer">
        <span>Desenvolvido com</span>
        <span><i class="icofont-heart text-danger mx-1"></i></span>
        <span>por devazevedo</span>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous">
    </script>
    <script src="{{ asset('js/app.js') }}"></script>
</body>

</html>
