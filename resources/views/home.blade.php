@extends('templates.layoutMain')
@section('conteudo')
    <main class="content">
        <div class="content-title mb-4">
            <div>
                <h1>{{'Seja bem vindo(a) '. session()->get('name') . ' ' . session()->get('lastname') }}</h1>
                <h2>Use seus conhecimentos futebolísticos para ganhar dinheiro</h2>
            </div>
        </div>
        {{-- <div class="col-lg-12 d-flex justify-content-around align-items-center content-body">
            <div class="col-lg-4 d-flex justify-content-around mt-4 info-dashboard">
                <h1>Sua pontuação</h1>
            </div>
            <div class="col-lg-4 d-flex justify-content-around mt-4 info-dashboard">
                <h1>Sua pontuação</h1>
            </div>
            <div class="col-lg-4 d-flex justify-content-around mt-4 info-dashboard">
                <h1>Sua pontuação</h1>
            </div>
        </div> --}}
        <div class="summary-boxes">
            <div class="summary-box bg-primary">
                <i class="icon icofont-soccer"></i>
                <p class="title">Pontuação Ao Vivo</p>
                <h3 class="value">{{ $pontuacao_ao_vivo }}</h3>
            </div>
            <div class="summary-box" style="background: rgba(210, 255, 27, 0.8);">
                <i class="icon icofont-bullseye"></i>
                <p class="title">Média acertos(%)</p>
                <h3 class="value">{{ $porcentagem_acertos }}%</h3>
            </div>
            <div class="summary-box bg-success">
                <i class="icon icofont-money-bag"></i>
                <p class="title">Saldo em conta</p>
                <h3 class="value">R$ {{ number_format($saldo, 2, ',', '.') }}</h3>
            </div>
        </div>
    </main>
@endsection