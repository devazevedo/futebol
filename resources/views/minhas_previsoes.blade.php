@extends('templates.layoutMain')
@section('style')
    <link rel="stylesheet" href="{{ asset('css/classificacao.css') }}">
@endsection
@section('conteudo')
    @php
        use Carbon\Carbon;
    @endphp
    <main class="content">
        <div class="content-title mb-4">
            <i class="icon icofont-bullseye mr-2"></i>
            <div>
                <h1>Minhas Previsões</h1>
                <h2>Reveja suas previsões e faça novas.</h2>
            </div>
        </div>
        <div class="col-lg-12" style="display: flex;">
            <div id="div-table-rounds" class="col-lg-6">
                <div id="div-rounds" class="mr-2">
                    <a href="{{ route('minhas_previsoes', ['rodada' => $rodada_anterior]) }}"><i
                            class="icofont-curved-left"></i></a>
                    <p>{{ $rodada_atual[0]->rodada }}</p>
                    <a href="{{ route('minhas_previsoes', ['rodada' => $rodada_seguinte]) }}"><i
                            class="icofont-curved-right"></i></a>
                </div>
                @foreach ($rodada_atual as $partida)
                    @if ($partida->status !== 'finalizado')
                        @php
                            $previsao = null;
                            foreach ($previsoes as $prev) {
                                if ($prev->partida_id === $partida->id) {
                                    $previsao = $prev;
                                    break;
                                }
                            }
                        @endphp

                        <div id="table-rounds" style="box-shadow: 11px 10px 27px -1px rgba(0,0,0,0.62); width: 100%;"
                            class="mb-5">
                            <p style="margin-top: -1rem;">{{ $partida->estadio }}</p>
                            <p>{{ $partida->data_realizacao }} - {{ $partida->hora_realizacao }}</p>
                            <div id="round">
                                @csrf
                                <img src="{{ $partida->time_mandante_escudo }}"
                                    alt="Escudo do {{ $partida->time_mandante_nome }}">
                                <p style="font-size: 2rem;"></p>
                                <input id="{{ 'placar-mandante-' . $partida->id }}"
                                    value="{{ $previsao ? $previsao->placar_mandante : '' }}" type="number">
                                <p>X</p>
                                <input id="{{ 'placar-visitante-' . $partida->id }}"
                                    value="{{ $previsao ? $previsao->placar_visitante : '' }}" type="number">
                                <p style="font-size: 2rem;"></p>
                                <img src="{{ $partida->time_visitante_escudo }}"
                                    alt="Escudo do {{ $partida->time_visitante_nome }}">
                            </div>
                            <div style="display: flex; align-items: center; justify-content: center; margin-top: 2px;">
                                <button class="enviar-previsao-btn btn btn-success btn-sm"
                                    data-partida-id="{{ $partida->id }}">
                                    Enviar Previsão
                                </button>
                            </div>
                        </div>
                    @else
                    {{-- fazer a logica para mostrar as partidas somente se tiver previsão --}}
                    @endif
                @endforeach
            </div>
            <div class="col-lg-6" style="max-height: 30vw; overflow-x: hidden; overflow-y: scroll;">
                <table class="table table-bordered table-sriped table-hover mt-4">
                    <thead>
                        <th style="text-align: center;">POS</th>
                        <th style="text-align: center;">Time</th>
                        <th style="text-align: center;">PTS</th>
                        <th style="text-align: center;">J</th>
                        <th style="text-align: center;">V</th>
                        <th style="text-align: center;">E</th>
                        <th style="text-align: center;">D</th>
                        <th style="text-align: center;">GP</th>
                        <th style="text-align: center;">GC</th>
                        <th style="text-align: center;">SG</th>
                        <th style="text-align: center;">%</th>
                        <th>Recentes</th>
                    </thead>
                    <tbody>
                        @foreach ($classificacao as $time)
                            @php
                                if ($time->variacao_posicao > 0) {
                                    $variacao = $time->variacao_posicao . "<i style='color: #66cc99;' class='icofont-arrow-up'></i>";
                                } elseif ($time->variacao_posicao < 0) {
                                    $variacao = abs($time->variacao_posicao) . "<i style='color: #dc3545;' class='icofont-arrow-down'></i>";
                                } else {
                                    $variacao = '';
                                }
                            @endphp
                            <tr>
                                <td style="text-align: center;">{{ $time->posicao }}</td>
                                <td><img class="mx-4" style="max-height: 2rem" src="{{ $time->escudo }}" alt="">
                                </td>
                                <td style="text-align: center;">{{ $time->pontos }}</td>
                                <td style="text-align: center;">{{ $time->jogos }}</td>
                                <td style="text-align: center;">{{ $time->vitorias }}</td>
                                <td style="text-align: center;">{{ $time->empates }}</td>
                                <td style="text-align: center;">{{ $time->derrotas }}</td>
                                <td style="text-align: center;">{{ $time->gols_pro }}</td>
                                <td style="text-align: center;">{{ $time->gols_contra }}</td>
                                <td style="text-align: center;">{{ $time->saldo_gols }}</td>
                                <td style="text-align: center;">{{ $time->aproveitamento }}%</td>
                                <td style="display: flex;">
                                    @foreach ($time->ultimos_jogos as $ultimos_jogos)
                                        @switch($ultimos_jogos)
                                            @case('v')
                                                <p style="background-color: #66cc99; margin-left:5px; border-radius: 50%; width: 20px; height: 20px; text-align: center;"
                                                    title="Vitória"></p>
                                            @break

                                            @case('e')
                                                <p style="background-color: #bbb; margin-left:5px; border-radius: 50%; width: 20px; height: 20px; text-align: center;"
                                                    title="Empate"></p>
                                            @break

                                            @case('d')
                                                <p style="background-color: #dc3545; margin-left:5px; border-radius: 50%; width: 20px; height: 20px; text-align: center;"
                                                    title="Derrota"></p>
                                            @break

                                            @default
                                        @endswitch
                                    @endforeach
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </main>
@endsection
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const enviarPrevisaoBtns = document.querySelectorAll('.enviar-previsao-btn');

        enviarPrevisaoBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const partidaId = this.getAttribute('data-partida-id');
                const placarMandante = document.querySelector(`#placar-mandante-${partidaId}`)
                    .value;
                const placarVisitante = document.querySelector(`#placar-visitante-${partidaId}`)
                    .value;

                axios.post('{{ route('enviar_previsao') }}', {
                        partida_id: partidaId,
                        placar_mandante: placarMandante,
                        placar_visitante: placarVisitante,
                    })
                    .then(response => {
                        if (response.data.status === 1) {
                            location.reload()
                        }
                    })
                    .catch(error => {
                        console.error(error);
                    });
            });
        });
    });
</script>
