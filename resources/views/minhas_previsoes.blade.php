@extends('templates.layoutMain')
@section('style')
    <link rel="stylesheet" href="{{ asset('css/classificacao.css') }}">
@endsection
@section('conteudo')
    @php
        use Carbon\Carbon;
    @endphp
    <main class="content">
        <div class="alert alert-success d-none">
            <p id="text-success"></p>
        </div>
        <div class="alert alert-danger d-none">
            <p id="text-error"></p>
        </div>
        <div class="content-title mb-4">
            <i class="icon icofont-bullseye mr-2"></i>
            <div>
                <h1>Minhas Previsões</h1>
                <h2>Reveja suas previsões e faça novas. <i onclick="infoPrevisoes()" style="font-size: 1.2rem; color: #1976d2; cursor: pointer;" class="icofont-info-circle"></i></h2>
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
                        @if ($rodada_atual[0]->status === 'finalizado')
                            <div id="table-rounds" style="box-shadow: 11px 10px 27px -1px rgba(0,0,0,0.62); width: 100%;"
                                class="mb-5">
                                <p style="margin-top: -1rem;">{{ $partida->estadio }}</p>
                                <p>{{ $partida->data_realizacao }} - {{ $partida->hora_realizacao }}</p>
                                <div id="round">
                                    @csrf
                                    <img src="{{ $partida->time_mandante_escudo }}"
                                        alt="Escudo do {{ $partida->time_mandante_nome }}">
                                    <p style="font-size: 2rem;">{{ $previsao ? $previsao->placar_mandante : '' }}</p>
                                    <p>X</p>
                                    <p style="font-size: 2rem;">{{ $previsao ? $previsao->placar_visitante : '' }}</p>
                                    <img src="{{ $partida->time_visitante_escudo }}"
                                        alt="Escudo do {{ $partida->time_visitante_nome }}">
                                </div>
                            </div>
                        @else
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
                        @endif
                    @else
                        @php
                            $previsao = null;
                            foreach ($previsoes as $prev) {
                                if ($prev->partida_id === $partida->id) {
                                    $previsao = $prev;
                                    break;
                                }
                            }
                            if(!empty($previsao)) {
                                switch ($previsao->status) {
                                    case 'parcial':
                                        $color = '#f0ff0e';
                                        $icon = 'icofont-minus';
                                        $title = 'Parcial';
                                        break;
                                    case 'errado': 
                                        $color = '#ff0018';
                                        $icon = 'icofont-close-line';
                                        $title = 'Errado';
                                        break;
                                    case 'certo':
                                        $color = '#07a31a';
                                        $icon = 'icofont-check-alt';
                                        $title = 'Certo';
                                        break;
                                    default:
                                        $color = '#fff';
                                        $icon = 'icofont-check-alt';
                                        break;
                                }
                            } else {
                                $color = '#fff';
                            }
                        @endphp
                        @if (!empty($previsao))
                            <div id="table-rounds" style="box-shadow: 11px 10px 27px -1px rgba(0,0,0,0.62); width: 100%;"
                                class="mb-5">
                                <div>
                                    <i title="<?= $title ?>" style="position: absolute; font-size: 1.5rem; background: <?= $color ?>; color: white; border-radius: 50%;" class="<?= $icon ?>"></i>
                                </div>
                                <p style="margin-top: -1rem;">{{ $partida->estadio }}</p>
                                <p>{{ $partida->data_realizacao }} - {{ $partida->hora_realizacao }}</p>
                                <div id="round">
                                    @csrf
                                    <img src="{{ $partida->time_mandante_escudo }}"
                                        alt="Escudo do {{ $partida->time_mandante_nome }}">
                                    <p style="font-size: 2rem;">{{ $previsao ? $previsao->placar_mandante : '' }}</p>
                                    <p>X</p>
                                    <p style="font-size: 2rem;">{{ $previsao ? $previsao->placar_visitante : '' }}</p>
                                    <img src="{{ $partida->time_visitante_escudo }}"
                                        alt="Escudo do {{ $partida->time_visitante_nome }}">
                                </div>
                                <div id="btn-show-details" class="<?= $partida->status === 'finalizado' ? '' : 'd-none' ?>">
                                    <a class="btn btn-primary" style="margin-top: 2px;"
                                        href="{{ route('detalhes_partida', ['partida_id' => $partida->id]) }}">Ver
                                        detalhes</a>
                                </div>
                            </div>
                        @endif
                @endif
                @endforeach
                @if (empty($previsoes) && $rodada_atual[0]->status === 'finalizado')
                    <div id="" style="margin-bottom: 26vw !important;">
                        <h4 class="mt-5" style="text-align: center">Você não efetuou previsões para essa rodada!</h4>
                    </div>
                @endif
            </div>
            <div class="col-lg-6">
                @if (!empty($previsoes))
                    <div id="div-info-previsao">
                        {{-- <div id="previsoes-certa" style="background:#1976d2c9;" class="card-previsoes">
                            <p>Pontuação</p>
                            <h1 style="text-align: center;">{{ $pontuacao }}</h1>
                        </div> --}}
                        <div id="previsoes-certa" style="background: #07a31a70;" class="card-previsoes">
                            <p>Certas:</p>
                            <h3 style="text-align: center;">{{ $qtdPrevisaoCerta ? $qtdPrevisaoCerta : 0 }}</h3>
                        </div>
                        <div id="previsoes-parcial" style="background: #f0ff0ead;" class="card-previsoes">
                            <p>Parcial:</p>
                            <h3 style="text-align: center;">{{ $qtdPrevisaoParcial ? $qtdPrevisaoParcial : 0}}</h3>
                        </div>
                        <div id="previsoes-erradas" style="background: #ff001873;" class="card-previsoes">
                            <p>Erradas:</p>
                            <h3 style="text-align: center;">{{ $qtdPrevisaoErrada ? $qtdPrevisaoErrada : 0}}</h3>
                        </div>
                        <div id="previsoes-aguardando" style="background: #bbbbbbbd;" class="card-previsoes">
                            <p>Aguardando:</p>
                            <h3 style="text-align: center;">{{ $qtdPrevisaoAguardando ? $qtdPrevisaoAguardando : 0}}</h3>
                        </div>
                    </div>
                    <div id="pontuacao">
                        <h4>Pontuação Total = <?= $pontuacao ?></h4>
                    </div>
                @endif
                <div style="max-height: 30vw; overflow-x: hidden; overflow-y: scroll; margin-top:2rem;">
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
        </div>
    </main>
    <div class="modal fade" id="infoModal" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="infoModalLabel">Como Funcionam as Previsões</h5>
                </div>
                <div class="modal-body">
                    <p><b>- Nesse momento suas previsões estão no modo <?= session()->get('previsao_paga') == 1 ? 'pago' : 'fictício' ?>.</b></p>
                    <p>- As previsões podem ser fictícias ou pagas, você pode alternar isso no seu perfil.</p>
                    @if (session()->get('previsao_paga') == 1)
                        <p>- Para as previsões no modo pago o dinheiro vai ser descontado e vai valer como previsão paga no momento que a ultima rodada ter a previsão efetuada.</p>
                        <p>- No modo pago caso você efetue a previsão de apenas 9 rodadas não será debitado valor da sua carteira logo você não estará concorrendo ao montante final.</p>
                        <p>- As previsões podem ser editadas até o início da primeira rodada(para as edições não será cobrado valor adicional).</p>
                        <p>- Caso você tenha sido o maior pontuador da rodada o dinheiro cairá na sua carteira em até 3 dias utéis.</p>
                        <p>- Se você for o único a fazer previsões na rodada o dinheiro será reembolsado na sua carteira.</p>
                    @else
                        <p>- Para as previsões no modo fictício não importa quantas partidas você prever no final da rodada vai aparecer sua pontuação mas você não vai estar concorrendo ao valor final.</p>
                        <p>- As previsões podem ser editadas até o início da primeira rodada.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
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
                            $('#text-success').html(response.data.message)
                            $('.alert-success').removeClass('d-none')
                            window.scrollTo(0, 0);
                            setTimeout(() => {
                                location.reload()
                            }, 2500);
                        }
                    })
                    .catch(error => {
                        console.error(error);
                    });
            });
        });
    });

    function infoPrevisoes() {
        $('#infoModal').modal('show');
    }
</script>
