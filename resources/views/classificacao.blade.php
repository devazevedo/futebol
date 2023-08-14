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
            <i class="icon icofont-numbered mr-2"></i>
            <div>
                <h1>Classificação</h1>
                <h2>Tabela de classificação dos times</h2>
            </div>
        </div>
        <div style="display: flex" class="col-lg-12">
            <div class="col-lg-9">
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
                                <td><img class="mx-4" style="max-height: 2.5rem" src="{{ $time->escudo }}"
                                        alt="">{{ $time->name }} <span class=""><?= $variacao ?></span></td>
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
            <div id="div-table-rounds" class="col-lg-3">
                <div id="div-rounds">
                    <a href="{{ route('classificacao', ['rodada' => $rodada_anterior]) }}"><i
                            class="icofont-curved-left"></i></a>
                    <p>{{ $rodada_atual[0]->rodada }}</p>
                    <a href="{{ route('classificacao', ['rodada' => $rodada_seguinte]) }}"><i
                            class="icofont-curved-right"></i></a>
                </div>
                @foreach ($rodada_atual as $partida)
                    <div id="table-rounds">
                        <p style="margin-top: -1rem;">{{ $partida->estadio }}</p>
                        <p>{{ $partida->data_realizacao }} - {{ $partida->hora_realizacao }}</p>
                        <div id="round">
                            <img src="{{ $partida->time_mandante_escudo }}"
                                alt="Escudo do {{ $partida->time_mandante_nome }}">
                            <p>{{ $partida->mandante_placar }}</p>
                            <p>X</p>
                            <p>{{ $partida->visitante_placar }}</p>
                            <img src="{{ $partida->time_visitante_escudo }}"
                                alt="Escudo do {{ $partida->time_visitante_nome }}">
                        </div>
                        <div id="btn-show-details" class="<?= $partida->status === 'finalizado' ? '' : 'd-none' ?>">
                            <a class="btn btn-primary" href="{{ route('detalhes_partida', ['partida_id' => $partida->id]) }}">Ver detalhes</a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </main>
@endsection
