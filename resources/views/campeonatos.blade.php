@extends('templates.layoutMain')
@section('style')
    <link rel="stylesheet" href="{{ asset('css/campeonatos.css') }}">
@endsection
@section('conteudo')
    @php
        use Carbon\Carbon;
    @endphp
    <main class="content">
        <div class="content-title mb-4">
            <i class="icon icofont-trophy-alt mr-2"></i>
            <div>
                <h1>Campeonatos</h1>
                <h2>Faça uma previsão do seu campeonato preferido!</h2>
            </div>
        </div>
        <div id="content-body">
            @foreach ($campeonatos as $campeonato)
                <div class="card-body">
                    <img src="{{ $campeonato->logo }}" alt="">
                    <p>{{ $campeonato->name }}</p>
                </div>
            @endforeach
        </div>
    </main>
@endsection
