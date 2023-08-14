@extends('templates.layoutMain')
@section('style')
    <link rel="stylesheet" href="{{ asset('css/detalhes_partida.css') }}">
@endsection
@section('conteudo')
    @php
        use Carbon\Carbon;
    @endphp
    <main class="content">
        <div class="content-title mb-4">
            <i class="icon icofont-chart-pie-alt mr-2"></i>
            <div>
                <h1>Estatísticas</h1>
                <h2>Mais informações da partida.</h2>
            </div>
        </div>
        <div class="content-body">
            <div class="header-details">
                <img src="{{ $timeMandante[0]->escudo }}" alt="">
                <h1>{{ $estatisticasMandante[0]->placar }}</h1>
                <p>X</p>
                <h1>{{ $estatisticasVisitante[0]->placar }}</h1>
                <img src="{{ $timeVisitante[0]->escudo }}" alt="">
            </div>
            <hr>
            <div class="content-details">
                <div class="statistics-details">
                    <p>{{ $estatisticasMandante[0]->finalizacao_total }}</p>
                    <p style="width: 42rem;">Chutes</p>
                    <p>{{ $estatisticasVisitante[0]->finalizacao_total }}</p>
                </div>
                <hr>
                <div class="statistics-details">
                    <p>{{ $estatisticasMandante[0]->finalizacao_no_gol }}</p>
                    <p style="width: 42rem;">Chutes no gol</p>
                    <p>{{ $estatisticasVisitante[0]->finalizacao_no_gol }}</p>
                </div>
                <hr>
                <div class="statistics-details">
                    <p>{{ $estatisticasMandante[0]->posse_de_bola }}</p>
                    <p style="width: 42rem;">Posse de bola</p>
                    <p>{{ $estatisticasVisitante[0]->posse_de_bola }}</p>
                </div>
                <hr>
                <div class="statistics-details">
                    <p>{{ $estatisticasMandante[0]->passes_total }}</p>
                    <p style="width: 42rem;">Passes</p>
                    <p>{{ $estatisticasVisitante[0]->passes_total }}</p>
                </div>
                <hr>
                <div class="statistics-details">
                    <p>{{ $estatisticasMandante[0]->passe_precisao }}</p>
                    <p style="width: 42rem;">Acerto de passes</p>
                    <p>{{ $estatisticasVisitante[0]->passe_precisao }}</p>
                </div>
                <hr>
                <div class="statistics-details">
                    <p>{{ $estatisticasMandante[0]->faltas }}</p>
                    <p style="width: 42rem;">Faltas</p>
                    <p>{{ $estatisticasVisitante[0]->faltas }}</p>
                </div>
                <hr>
                <div class="statistics-details">
                    <p>{{ $estatisticasMandante[0]->faltas }}</p>
                    <p style="width: 42rem;">Cartões amarelos</p>
                    <p>{{ $estatisticasVisitante[0]->faltas }}</p>
                </div>
                <hr>
                <div class="statistics-details">
                    <p>{{ $estatisticasMandante[0]->faltas }}</p>
                    <p style="width: 42rem;">Cartões vermelhos</p>
                    <p>{{ $estatisticasVisitante[0]->faltas }}</p>
                </div>
                <hr>
                <div class="statistics-details">
                    <p>{{ $estatisticasMandante[0]->impedimentos }}</p>
                    <p style="width: 42rem;">Impedimento</p>
                    <p>{{ $estatisticasVisitante[0]->impedimentos }}</p>
                </div>
                <hr>
                <div class="statistics-details">
                    <p>{{ $estatisticasMandante[0]->escanteios }}</p>
                    <p style="width: 42rem;">Escanteios</p>
                    <p>{{ $estatisticasVisitante[0]->escanteios }}</p>
                </div>
                <hr>
            </div>
        </div>
    </main>
@endsection
