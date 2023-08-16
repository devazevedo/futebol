<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Apis;
use App\Models\Futebol_model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class Api extends Controller
{
    public function apis()
    {
        if (session()->get('isAdmin') === 0) {
            return view('home');
        } else {
            $model = new Apis;
            $apis = $model->getApis();

            $data = [
                'apis' => $apis
            ];

            return view('apis', $data);
        }
    }

    public function  execute_api($id)
    {
        if (session()->get('isAdmin') === 0) {
            return view('home');
        }

        $model = new Apis;
        $api = $model->getApiById($id);

        if (!$api) {
            $data = [
                'status' => 0,
                'message' => 'Tivemos um problema ao executar essa api, tente novamente mais tarde!'
            ];

            return view('apis', $data);
        }

        //chaves conta principal
        // $bearerToken = 'live_2b41b3a47aa7678d8b15d0e3737529';
        // $bearerToken = 'test_74cd948f12857040d7801cc8c28bcb';

        //chaves segunda conta
        $bearerToken = 'live_15a06652e8949ebe225ec6b8c60239';

        if ($api[0]->id !== 4) {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $bearerToken,
            ])->get($api[0]->url);
        }

        $model = new Apis;
        $apis = $model->getApis();
        $model->update($id);

        $futebol = new Futebol_model;

        if ($api[0]->id !== 4 && $response->successful()) {
            $responseData = $response->json();


            switch ($api[0]->name) {
                case 'Listar campeonatos':
                    foreach ($responseData as $campeonato) {
                        $campeonato_id = $campeonato['campeonato_id'];
                        $name = $campeonato['nome'];
                        $popular_name = $campeonato['nome_popular'];
                        $season = $campeonato['edicao_atual']['temporada'];

                        if (!empty($campeonato['rodada_atual'])) {
                            $round = $campeonato['rodada_atual']['nome'];
                            $rodada_atual = intval($campeonato['rodada_atual']['nome']);
                        } else {
                            $round = 'Não definido';
                            $rodada_atual = 0;
                        }

                        $phase = $campeonato['fase_atual']['nome'];
                        $status = $campeonato['status'];
                        $logo = $campeonato['logo'];

                        $camp = $futebol->getCampeonatoById($campeonato_id);

                        if (empty($camp)) {
                            $futebol->insertCampeonato($campeonato_id, $name, $popular_name, $rodada_atual, $season, $round, $phase, $status, $logo);
                        }
                    }

                    $data = [
                        'status' => 1,
                        'message' => 'Campeonatos inseridos com sucesso!',
                        'apis' => $apis
                    ];
                    break;
                case 'Tabela Brasileirão Série A':
                    foreach ($responseData as $posicoes) {
                        $id_time = $posicoes['time']['time_id'];
                        $nome_time = $posicoes['time']['nome_popular'];
                        $sigla_time = $posicoes['time']['sigla'];
                        $escudo_time = $posicoes['time']['escudo'];

                        $time = $futebol->getTimes($id_time);

                        if (empty($time)) {
                            $futebol->insertTime($nome_time, $id_time, $sigla_time, $escudo_time);
                        }

                        $posicao = $posicoes['posicao'];
                        $pontos = $posicoes['pontos'];
                        $jogos = $posicoes['jogos'];
                        $vitorias = $posicoes['vitorias'];
                        $empates = $posicoes['empates'];
                        $derrotas = $posicoes['derrotas'];
                        $gols_pro = $posicoes['gols_pro'];
                        $gols_contra = $posicoes['gols_contra'];
                        $saldo_gols = $posicoes['saldo_gols'];
                        $aproveitamento = $posicoes['aproveitamento'];
                        $variacao_posicao = $posicoes['variacao_posicao'];
                        $ultimos_jogos = '';
                        foreach ($posicoes['ultimos_jogos'] as $key => $value) {
                            if ($key != 4) {
                                $ultimos_jogos .= $value . ',';
                            } else {
                                $ultimos_jogos .= $value;
                            }
                        }

                        $classificacao = $futebol->getTimesInseridosCampeonato(10, $id_time);

                        if (empty($classificacao)) {
                            $futebol->insertTimeTabelaClassificacao($id_time, 10, $posicao, $pontos, $jogos, $vitorias, $empates, $derrotas, $gols_pro, $gols_contra, $saldo_gols, $aproveitamento, $variacao_posicao, $ultimos_jogos);
                        } else {
                            $futebol->updateTimeTabelaClassificacao($id_time, 10, $posicao, $pontos, $jogos, $vitorias, $empates, $derrotas, $gols_pro, $gols_contra, $saldo_gols, $aproveitamento, $variacao_posicao, $ultimos_jogos);
                        }
                    }
                    $data = [
                        'status' => 1,
                        'message' => 'Tabela inserida com sucesso!',
                        'apis' => $apis
                    ];
                    break;
                case 'Partidas Brasileirão Série A':
                    foreach ($responseData['partidas'] as $key => $partidasRodada) {
                        foreach ($partidasRodada as $rodada => $partidaRodada) {
                            foreach ($partidaRodada as $partida) {
                                $partida_id = $partida['partida_id'];
                                $campeonato_id = 10;
                                $id_time_mandante = $partida['time_mandante']['time_id'];
                                $id_time_visitante = $partida['time_visitante']['time_id'];
                                $data_realizacao = $partida['data_realizacao'];
                                $hora_realizacao = $partida['hora_realizacao'];
                                $status = $partida['status'];

                                $partidaExistente = $futebol->getPartidas($partida_id);

                                $rodadaSoNumero = intval($rodada);
                                $estadio = 'Não definido';

                                if (empty($partidaExistente)) {
                                    $futebol->insertPartida($partida_id, $campeonato_id, $id_time_mandante, $id_time_visitante, $rodadaSoNumero, $estadio, $data_realizacao, $hora_realizacao, $status);
                                } else {
                                    $futebol->updatePartida($partida_id, $campeonato_id, $id_time_mandante, $id_time_visitante, $rodadaSoNumero, $estadio, $data_realizacao, $hora_realizacao, $status);
                                }
                            }
                        }
                    }

                    $data = [
                        'status' => 1,
                        'message' => 'Partidas inseridas com sucesso!',
                        'apis' => $apis
                    ];
                    break;
                default:
                    $data = [
                        'status' => 0,
                        'message' => 'Tivemos um problema ao executar essa api, tente novamente mais tarde!',
                        'apis' => $apis
                    ];
                    break;
            }
            return view('apis', $data);
        } else if ($api[0]->id === 4) {
            $partidas = $futebol->getPartidas();

            foreach ($partidas as $partida) {

                $estatisticaPartida = $futebol->getEstatisticaByIdPartida($partida->id);

                if (empty($estatisticaPartida)) {

                    if (!$api) {
                        $data = [
                            'status' => 0,
                            'message' => 'Tivemos um problema ao executar essa api, tente novamente mais tarde!'
                        ];

                        return view('apis', $data);
                    }

                    $response2 = Http::withHeaders([
                        'Authorization' => 'Bearer ' . $bearerToken,
                    ])->get($api[0]->url . $partida->id);

                    if ($response2->successful()) {
                        $dadosPartida = $response2->json();

                        if ($dadosPartida['status'] === 'finalizado') {

                            //insercao estatisticas mandante
                            $placar_mandante = $dadosPartida['placar_mandante'];
                            $posse_bola_mandante = $dadosPartida['estatisticas']['mandante']['posse_de_bola'];
                            $escanteios_mandante = $dadosPartida['estatisticas']['mandante']['escanteios'];
                            $impedimentos_mandante = $dadosPartida['estatisticas']['mandante']['impedimentos'];
                            $faltas_mandante = $dadosPartida['estatisticas']['mandante']['faltas'];
                            $passes_total_mandante = $dadosPartida['estatisticas']['mandante']['passes']['total'];
                            $passes_completos_mandante = $dadosPartida['estatisticas']['mandante']['passes']['completos'];
                            $passes_errados_mandante = $dadosPartida['estatisticas']['mandante']['passes']['errados'];
                            $passes_precisao_mandante = $dadosPartida['estatisticas']['mandante']['passes']['precisao'];
                            $finalizacao_total_mandante = $dadosPartida['estatisticas']['mandante']['finalizacao']['total'];
                            $finalizacao_no_gol_mandante = $dadosPartida['estatisticas']['mandante']['finalizacao']['no_gol'];
                            $finalizacao_pra_fora_mandante = $dadosPartida['estatisticas']['mandante']['finalizacao']['pra_fora'];
                            $finalizacao_na_trave_mandante = $dadosPartida['estatisticas']['mandante']['finalizacao']['na_trave'];
                            $finalizacao_bloqueado_mandante = $dadosPartida['estatisticas']['mandante']['finalizacao']['bloqueado'];
                            $finalizacao_precisao_mandante = $dadosPartida['estatisticas']['mandante']['finalizacao']['precisao'];
                            $cartoes_amarelos_mandante = count($dadosPartida['cartoes']['amarelo']['mandante']);
                            $cartoes_vermelhos_mandante = count($dadosPartida['cartoes']['vermelho']['mandante']);
                            $defesas_mandante = $dadosPartida['estatisticas']['mandante']['defensivo']['defesas'];
                            $desarmes_mandante = $dadosPartida['estatisticas']['mandante']['desarmes'];
                            $escalacaoMandante = $dadosPartida['escalacoes']['mandante']['esquema_tatico'];

                            $escalacao = $dadosPartida['escalacoes'];
                            $gols = $dadosPartida['gols'];
                            $substituicoes = $dadosPartida['substituicoes'];
                            
                            foreach ($escalacao['mandante']['titulares'] as $jogador) {
                                $jogadorId = $jogador['atleta']['atleta_id'];
                                $jogadorNome = $jogador['atleta']['nome_popular'];
                                $camisa = $jogador['camisa'];
                                $ordem = $jogador['ordem'];
                                if(!empty($jogador['posicao'])){
                                    $nomePosicao = $jogador['posicao']['nome'];
                                    $sigla = $jogador['posicao']['sigla'];
                                } else {
                                    $nomePosicao = 'Não definido';
                                    $sigla = 'ND';
                                }
                                $idTime = $dadosPartida['time_mandante']['time_id'];
                                
                                $jogadorById = $futebol->getJogadores($jogadorId);
                                
                                if(empty($jogadorById)) {
                                    $futebol->insertJogadores($jogadorId, $jogadorNome, $camisa, $nomePosicao, $sigla, $idTime);
                                }

                                $futebol->insertEscalacaoPartida($partida->id, $jogadorId, null, $ordem, 1, 'mandante');
                            }

                            foreach ($escalacao['mandante']['reservas'] as $jogador) {
                                $jogadorId = $jogador['atleta']['atleta_id'];
                                $jogadorNome = $jogador['atleta']['nome_popular'];
                                $camisa = $jogador['camisa'];
                                $ordem = $jogador['ordem'];
                                if(!empty($jogador['posicao'])){
                                    $nomePosicao = $jogador['posicao']['nome'];
                                    $sigla = $jogador['posicao']['sigla'];
                                } else {
                                    $nomePosicao = 'Não definido';
                                    $sigla = 'ND';
                                }
                                $idTime = $dadosPartida['time_mandante']['time_id'];
                                
                                $jogadorById = $futebol->getJogadores($jogadorId);
                                
                                if(empty($jogadorById)) {
                                    $futebol->insertJogadores($jogadorId, $jogadorNome, $camisa, $nomePosicao, $sigla, $idTime);
                                }

                                $futebol->insertEscalacaoPartida($partida->id, $jogadorId, null, $ordem, 0, 'mandante');
                            }

                            $idTecnicoMandante = $dadosPartida['escalacoes']['mandante']['tecnico']['tecnico_id'];
                            $nomeTecnicoMandante = $dadosPartida['escalacoes']['mandante']['tecnico']['nome_popular'];

                            $tecnicoById = $futebol->getTreinadorById($idTecnicoMandante);

                            if(empty($tecnicoById)) {
                                $futebol->insertTreinadores($idTecnicoMandante, $nomeTecnicoMandante);
                            }

                            $futebol->insertEscalacaoPartida($partida->id, null, $idTecnicoMandante, 0, 2, 'mandante');

                            if(!empty($gols['mandante'])){
                                foreach ($gols['mandante'] as $gol) {
                                    $jogadorIdGol = $gol['atleta']['atleta_id'];
                                    $minutoGol = $gol['minuto'];
                                    $periodoGol = $gol['periodo'];
                                    $golPenalti = empty($gol['penalti']) ? false : true;
                                    $golContra = empty($gol['gol_contra']) ? false : true;
                                    
                                    $futebol->insertGols($partida->id, $jogadorIdGol, $minutoGol, $periodoGol, $golPenalti, $golContra, 'mandante');
                                }
                            }

                            if(!empty($substituicoes['mandante'])){
                                foreach ($substituicoes['mandante'] as $substituicao) {
                                    $jogadorIdSaiu = $substituicao['saiu']['atleta_id'];
                                    $jogadorIdEntrou = $substituicao['entrou']['atleta_id'];
                                    $periodo = $substituicao['periodo'];
                                    $minuto = $substituicao['minuto'];
                                    
                                    $futebol->insertSubstituicoes($partida->id, $jogadorIdSaiu, $jogadorIdEntrou, $periodo, $minuto);
                                }
                            }

                            //insercao estatisticas visitante
                            $placar_visitante = $dadosPartida['placar_visitante'];
                            $posse_bola_visitante = $dadosPartida['estatisticas']['visitante']['posse_de_bola'];
                            $escanteios_visitante = $dadosPartida['estatisticas']['visitante']['escanteios'];
                            $impedimentos_visitante = $dadosPartida['estatisticas']['visitante']['impedimentos'];
                            $faltas_visitante = $dadosPartida['estatisticas']['visitante']['faltas'];
                            $passes_total_visitante = $dadosPartida['estatisticas']['visitante']['passes']['total'];
                            $passes_completos_visitante = $dadosPartida['estatisticas']['visitante']['passes']['completos'];
                            $passes_errados_visitante = $dadosPartida['estatisticas']['visitante']['passes']['errados'];
                            $passes_precisao_visitante = $dadosPartida['estatisticas']['visitante']['passes']['precisao'];
                            $finalizacao_total_visitante = $dadosPartida['estatisticas']['visitante']['finalizacao']['total'];
                            $finalizacao_no_gol_visitante = $dadosPartida['estatisticas']['visitante']['finalizacao']['no_gol'];
                            $finalizacao_pra_fora_visitante = $dadosPartida['estatisticas']['visitante']['finalizacao']['pra_fora'];
                            $finalizacao_na_trave_visitante = $dadosPartida['estatisticas']['visitante']['finalizacao']['na_trave'];
                            $finalizacao_bloqueado_visitante = $dadosPartida['estatisticas']['visitante']['finalizacao']['bloqueado'];
                            $finalizacao_precisao_visitante = $dadosPartida['estatisticas']['visitante']['finalizacao']['precisao'];
                            $cartoes_amarelos_visitante = count($dadosPartida['cartoes']['amarelo']['visitante']);
                            $cartoes_vermelhos_visitante = count($dadosPartida['cartoes']['vermelho']['visitante']);
                            $defesas_visitante = $dadosPartida['estatisticas']['visitante']['defensivo']['defesas'];
                            $desarmes_visitante = $dadosPartida['estatisticas']['visitante']['desarmes'];
                            $escalacaoVisitante = $dadosPartida['escalacoes']['visitante']['esquema_tatico'];

                            foreach ($escalacao['visitante']['titulares'] as $jogador) {
                                $jogadorId = $jogador['atleta']['atleta_id'];
                                $jogadorNome = $jogador['atleta']['nome_popular'];
                                $camisa = $jogador['camisa'];
                                $ordem = $jogador['ordem'];
                                if(!empty($jogador['posicao'])){
                                    $nomePosicao = $jogador['posicao']['nome'];
                                    $sigla = $jogador['posicao']['sigla'];
                                } else {
                                    $nomePosicao = 'Não definido';
                                    $sigla = 'ND';
                                }
                                $idTime = $dadosPartida['time_visitante']['time_id'];
                                
                                $jogadorById = $futebol->getJogadores($jogadorId);
                                
                                if(empty($jogadorById)) {
                                    $futebol->insertJogadores($jogadorId, $jogadorNome, $camisa, $nomePosicao, $sigla, $idTime);
                                }

                                $futebol->insertEscalacaoPartida($partida->id, $jogadorId, null, $ordem, 1, 'visitante');
                            }

                            foreach ($escalacao['visitante']['reservas'] as $jogador) {
                                $jogadorId = $jogador['atleta']['atleta_id'];
                                $jogadorNome = $jogador['atleta']['nome_popular'];
                                $camisa = $jogador['camisa'];
                                $ordem = $jogador['ordem'];
                                if(!empty($jogador['posicao'])){
                                    $nomePosicao = $jogador['posicao']['nome'];
                                    $sigla = $jogador['posicao']['sigla'];
                                } else {
                                    $nomePosicao = 'Não definido';
                                    $sigla = 'ND';
                                }
                                $idTime = $dadosPartida['time_visitante']['time_id'];
                                
                                $jogadorById = $futebol->getJogadores($jogadorId);
                                
                                if(empty($jogadorById)) {
                                    $futebol->insertJogadores($jogadorId, $jogadorNome, $camisa, $nomePosicao, $sigla, $idTime);
                                }

                                $futebol->insertEscalacaoPartida($partida->id, $jogadorId, null, $ordem, 0, 'visitante');
                            }

                            $idTecnicoVisitante = $dadosPartida['escalacoes']['visitante']['tecnico']['tecnico_id'];
                            $nomeTecnicoVisitante = $dadosPartida['escalacoes']['visitante']['tecnico']['nome_popular'];

                            $tecnicoById = $futebol->getTreinadorById($idTecnicoVisitante);

                            if(empty($tecnicoById)) {
                                $futebol->insertTreinadores($idTecnicoVisitante, $nomeTecnicoVisitante);
                            }

                            $futebol->insertEscalacaoPartida($partida->id, null, $idTecnicoVisitante, 0, 2, 'visitante');

                            if(!empty($gols['visitante'])){
                                foreach ($gols['visitante'] as $gol) {
                                    $jogadorIdGol = $gol['atleta']['atleta_id'];
                                    $minutoGol = $gol['minuto'];
                                    $periodoGol = $gol['periodo'];
                                    $golPenalti = empty($gol['penalti']) ? false : true;
                                    $golContra = empty($gol['gol_contra']) ? false : true;
                                    
                                    $futebol->insertGols($partida->id, $jogadorIdGol, $minutoGol, $periodoGol, $golPenalti, $golContra, 'visitante');
                                }
                            }

                            if(!empty($substituicoes['visitante'])){
                                foreach ($substituicoes['visitante'] as $substituicao) {
                                    $jogadorIdSaiu = $substituicao['saiu']['atleta_id'];
                                    $jogadorIdEntrou = $substituicao['entrou']['atleta_id'];
                                    $periodo = $substituicao['periodo'];
                                    $minuto = $substituicao['minuto'];
                                    
                                    $futebol->insertSubstituicoes($partida->id, $jogadorIdSaiu, $jogadorIdEntrou, $periodo, $minuto);
                                }
                            }

                            $futebol->insertEstatisticas($partida->id, $partida->id_time_visitante, $placar_visitante, $posse_bola_visitante, $escanteios_visitante, $impedimentos_visitante, $faltas_visitante, $passes_total_visitante, $passes_completos_visitante, $passes_errados_visitante, $passes_precisao_visitante, $finalizacao_total_visitante, $finalizacao_no_gol_visitante, $finalizacao_pra_fora_visitante, $finalizacao_na_trave_visitante, $finalizacao_bloqueado_visitante, $finalizacao_precisao_visitante, $cartoes_amarelos_visitante, $cartoes_vermelhos_visitante, $defesas_visitante, $desarmes_visitante, $escalacaoMandante);
                            $futebol->insertEstatisticas($partida->id, $partida->id_time_mandante, $placar_mandante, $posse_bola_mandante, $escanteios_mandante, $impedimentos_mandante, $faltas_mandante, $passes_total_mandante, $passes_completos_mandante, $passes_errados_mandante, $passes_precisao_mandante, $finalizacao_total_mandante, $finalizacao_no_gol_mandante, $finalizacao_pra_fora_mandante, $finalizacao_na_trave_mandante, $finalizacao_bloqueado_mandante, $finalizacao_precisao_mandante, $cartoes_amarelos_mandante, $cartoes_vermelhos_mandante, $defesas_mandante, $desarmes_mandante, $escalacaoVisitante);
                            $previsoes = $futebol->getPrevisoes(null, $partida->id);

                            if($partida->status === 'agendado') {
                                $futebol->updateStatusPartida($partida->id, $dadosPartida['status']);
                            }

                            if(!empty($previsoes)){
                                foreach ($previsoes as $previsao) {
                                    if($previsao->placar_mandante === $placar_mandante && $previsao->placar_visitante === $placar_visitante) {
                                        $statusPrevisao = 'certo';
                                    } else if ($previsao->placar_mandante === $previsao->placar_visitante && $placar_mandante === $placar_visitante) {
                                        $statusPrevisao = 'parcial';
                                    } else if ($previsao->placar_mandante > $previsao->placar_visitante && $placar_mandante > $placar_visitante) {
                                        $statusPrevisao = 'parcial';
                                    } else if ($previsao->placar_mandante < $previsao->placar_visitante && $placar_mandante < $placar_visitante) {
                                        $statusPrevisao = 'parcial';
                                    } else {
                                        $statusPrevisao = 'errado';
                                    }
    
                                    $futebol->updatePrevisao($previsao->id, $statusPrevisao);
                                }
                            }
                        }

                        $gols = $dadosPartida['gols'];

                        if(!empty($gols['visitante'])){
                            foreach ($gols['visitante'] as $gol) {
                                $jogadorIdGol = $gol['atleta']['atleta_id'];
                                $minutoGol = $gol['minuto'];
                                $periodoGol = $gol['periodo'];
                                $golPenalti = empty($gol['penalti']) ? false : true;
                                $golContra = empty($gol['gol_contra']) ? false : true;

                                $gol = $futebol->getGol($partida->id, $jogadorIdGol, $minutoGol);
                                
                                $futebol->updateGols($gol[0]->id, 'visitante');
                            }
                        }

                        if(!empty($gols['mandante'])){
                            foreach ($gols['mandante'] as $gol) {
                                $jogadorIdGol = $gol['atleta']['atleta_id'];
                                $minutoGol = $gol['minuto'];
                                $periodoGol = $gol['periodo'];
                                $golPenalti = empty($gol['penalti']) ? false : true;
                                $golContra = empty($gol['gol_contra']) ? false : true;
                                
                                $gol = $futebol->getGol($partida->id, $jogadorIdGol, $minutoGol);
                                
                                $futebol->updateGols($gol[0]->id, 'mandante');
                            }
                        }

                        $rodada = $dadosPartida['campeonato']['rodada_atual']['rodada']; // rodada atual
                        if(empty($dadosPartida['estadio'])) {
                            $estadio = 'Não definido';
                        } else {
                            $estadio = $dadosPartida['estadio']['nome_popular'];
                        }

                        $futebol->updateEstadioPartida($partida->id, $estadio);
                        
                    } else {
                        $errorMessage = $response2->json()['error'] ?? 'Erro desconhecido';
                        print_r($response2->json());
                        $data = [
                            'status' => 0,
                            'message' => 'Tivemos um problema ao executar essa api, tente novamente mais tarde!'
                        ];

                        return view('apis', $data);
                    }
                }
            }
            $data = [
                'status' => 1,
                'message' => 'Estatisticas inseridas com sucesso'
            ];

            return view('apis', $data);
        } else {
            $errorMessage = $response->json()['error'] ?? 'Erro desconhecido';
            print_r($response->json());
        }

        // Redirecionar ou retornar uma view com as informações relevantes
        // ...
    }
}
