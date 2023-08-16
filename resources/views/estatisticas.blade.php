@extends('templates.layoutMain')
@section('style')
    <link rel="stylesheet" href="{{ asset('css/estatisticas.css') }}">
@endsection
@section('conteudo')
    @php
        use Carbon\Carbon;
    @endphp
    <main class="content">
        <div class="content-title mb-4">
            <i class="icon icofont-pie-chart mr-2"></i>
            <div>
                <h1>Estatísticas</h1>
                <h2>Acompanhe as estatísticas gerais</h2>
            </div>
        </div>
        <div class="filters col-lg-12 mt-4">
            <form class="form-estatisticas" action="{{ route('estatisticas') }}" method="post">
                @csrf
                <div class="form-group col-lg-5 mt-2">
                    <label for="campeonato">Campeonato</label>
                    <select class="form-control" name="campeonato" id="campeonato">
                        @foreach ($campeonatos as $campeonato)
                            @if ($campeonato->id === 10)
                                <option value="{{ $campeonato->id }}">{{ $campeonato->popular_name }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-lg-5 mt-2">
                    <label for="time">Time</label>
                    <select class="form-control" name="time" id="time" onchange="enabledFilter(this)">
                        <option value="">Selecione</option>
                        @foreach ($times as $time)
                            <option {{ $timeSelecionado == $time->id ? 'selected' : '' }} value="{{ $time->id }}">
                                {{ $time->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-lg-2 mt-2" style="align-self: end;">
                    <input id="btn-filter" disabled class="btn btn-primary" type="submit" value="Filtrar">
                </div>
                {{-- <div class="form-group col-lg-3 mt-2">
                    <label for="jogador">Jogador</label>
                    <select class="form-control" name="jogador" id="jogador">
                        <option value="">Selecione</option>
                        @foreach ($jogadores as $jogador)
                            <option value="{{ $jogador->id }}">{{ $jogador->nome }}</option>
                        @endforeach
                    </select>
                </div> --}}
                {{-- <div class="form-group col-lg-4 mt-2">
                    <label for="por">Por</label>
                    <select class="form-control" name="por" id="por">
                        <option value="">Finalização</option>
                        <option value="">Aproveitamento</option>
                        <option value="">Esca</option>
                    </select>
                </div> --}}
            </form>
        </div>
        <div class="estatisticas col-lg-12 mt-4 d-flex" style="justify-content: space-around;">
            @if ($medias)
                @foreach ($medias as $media)
                    <div class="col-lg-4">
                        <h2>Finalizações</h2>
                        <canvas id="graficoFinalizacoes"></canvas>
                    </div>
                    <div class="col-lg-4 ml-4">
                        <h2>Passes</h2>
                        <canvas id="graficoPasses"></canvas>
                    </div>
                @endforeach
            @endif
        </div>
        <hr>
        <div class="estatisticas2 col-lg-12 mt-4 d-flex" style="align-items: center; justify-content: space-around">
            @if ($medias)
                @foreach ($medias as $media)
                    <div class="col-lg-6">
                        <h2>Porcentagem</h2>
                        <canvas id="graficoGeral"></canvas>
                    </div>
                @endforeach
            @endif
        </div>
    </main>
@endsection
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    window.onload = (event) => {
        @if ($medias)
            var ctx = document.getElementById('graficoFinalizacoes').getContext('2d');
            var ctx2 = document.getElementById('graficoPasses').getContext('2d');
            var ctx3 = document.getElementById('graficoGeral').getContext('2d');

            var myChart = new Chart(ctx, {
                type: 'polarArea',
                data: {
                    labels: ['No Gol', 'Pra Fora', 'Na Trave', 'Bloqueada'],
                    datasets: [{
                        label: 'Finalizações',
                        data: [
                            {{ round($media->finalizacao_no_gol, 2) }},
                            {{ round($media->finalizacao_pra_fora, 2) }},
                            {{ round($media->finalizacao_na_trave, 2) }},
                            {{ round($media->finalizacao_bloqueado, 2) }}
                        ],
                        backgroundColor: [
                            'rgba(43, 178, 36, 0.8)',
                            'rgba(192, 50, 50, 0.8)',
                            'rgba(0, 119, 165, 0.8)',
                            'rgba(210, 255, 27, 0.8)',
                        ],
                        borderColor: 'rgba(255, 255, 255, 0.8)',
                        borderWidth: 1,
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        r: {
                            pointLabels: {
                                display: true,
                                centerPointLabels: true,
                                font: {
                                    size: 18
                                }
                            }
                        }
                    }
                }
            });

            var myChart = new Chart(ctx2, {
                type: 'polarArea',
                data: {
                    labels: ['Completos', 'Errados'],
                    datasets: [{
                        label: 'Passes',
                        data: [
                            {{ round($media->passes_completos, 2) }},
                            {{ round($media->passes_errados, 2) }}
                        ],
                        backgroundColor: [
                            'rgba(43, 178, 36, 0.8)',
                            'rgba(192, 50, 50, 0.8)',
                        ],
                        borderColor: 'rgba(255, 255, 255, 0.8)',
                        borderWidth: 1,
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        r: {
                            pointLabels: {
                                display: true,
                                centerPointLabels: true,
                                font: {
                                    size: 18
                                }
                            }
                        }
                    }
                }
            });

            var myChart = new Chart(ctx3, {
                type: 'polarArea',
                data: {
                    labels:['Posse de bola', 'Finalização', 'Passes'],
                    datasets: [{
                        label: 'Porcentagem',
                        data: [
                            {{ round($media->posse_de_bola, 2) }},
                            {{ round($media->finalizacao_precisao, 2) }},
                            {{ round($media->passe_precisao, 2) }}
                        ],
                        backgroundColor: [
                            'rgba(43, 178, 36, 0.8)',
                            'rgba(210, 255, 27, 0.8)',
                            'rgba(0, 119, 165, 0.8)',
                        ],
                        borderColor: 'rgba(255, 255, 255, 0.8)',
                        borderWidth: 1,
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        r: {
                            pointLabels: {
                                display: true,
                                centerPointLabels: true,
                                font: {
                                    size: 18
                                }
                            }
                        }
                    }
                }
            });
        @endif
    };
</script>
