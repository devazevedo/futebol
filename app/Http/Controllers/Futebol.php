<?php

namespace App\Http\Controllers;

use App\Models\Futebol_model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Futebol extends Controller
{
    public function campeonatos()
    {
        $model = new Futebol_model;

        $campeonatos = $model->getCampeonatos();

        $data = [
            'campeonatos' => $campeonatos
        ];

        return view('campeonatos', $data);
    }

    public function classificacao($rodada = null)
    {
        $model = new Futebol_model;

        $classificacao = $model->getClassificacaoTimesByIdCampeonato(10);
        if(empty($rodada)) {
            $rodada_atual = $model->getPartidasRodada();
        } else {
            $rodada_atual = $model->getPartidasRodada($rodada);
        }

        foreach ($rodada_atual as $partida) {
            $rodadaNumero = $partida->rodada;
            $partida->rodada = 'Rodada ' . $partida->rodada;
        }

        foreach ($classificacao as $item) {
            $ultimos_jogos_array = explode(',', $item->ultimos_jogos);
            $item->ultimos_jogos = $ultimos_jogos_array;
        }

        if(!empty($rodada_atual)) {
            $data = [
                'classificacao' => $classificacao,
                'rodada_atual' => $rodada_atual,
                'rodada_anterior' => $rodadaNumero - 1,
                'rodada_seguinte' => $rodadaNumero + 1
            ];

            return view('classificacao', $data);
        }

        $data = [
            'status' => 0,
            'message' => 'Estamos com uma estabilidade no momento, tente novamente mais tarde.'
        ];

        return view('home');
    }

    public function detalhes_partida($id_partida)
    {

        $model = new Futebol_model;

        $partida = $model->getPartidas($id_partida);

        $estatisticasMandante = $model->getEstatisticaByIdPartida($id_partida, $partida[0]->id_time_mandante);
        $estatisticasVisitante = $model->getEstatisticaByIdPartida($id_partida, $partida[0]->id_time_visitante);

        $timeMandante = $model->getTimeById($partida[0]->id_time_mandante);
        $timeVisitante = $model->getTimeById($partida[0]->id_time_visitante);

        $data = [
            'partida' => $partida,
            'estatisticasMandante' => $estatisticasMandante,
            'estatisticasVisitante' => $estatisticasVisitante,
            'timeMandante' => $timeMandante,
            'timeVisitante' => $timeVisitante
        ];

        return view('detalhes_partida', $data);
    }

    public function minhas_previsoes($rodada = null)
    {
        $model = new Futebol_model;

        if(empty($rodada)) {
            $rodada_atual = $model->getPartidasRodada();
        } else {
            $rodada_atual = $model->getPartidasRodada($rodada);
        }

        foreach ($rodada_atual as $partida) {
            $rodadaNumero = $partida->rodada;
            $partida->rodada = 'Rodada ' . $partida->rodada;
        }

        $classificacao = $model->getClassificacaoTimesByIdCampeonato(10);

        foreach ($classificacao as $item) {
            $ultimos_jogos_array = explode(',', $item->ultimos_jogos);
            $item->ultimos_jogos = $ultimos_jogos_array;
        }

        $user_id = session()->get('userId');

        $previsoes = $model->getPrevisoesByUserIdAndRodada($user_id, $rodadaNumero);

        $data = [
            'rodada_atual' => $rodada_atual,
            'classificacao' => $classificacao,
            'previsoes' => $previsoes,
            'rodada_anterior' => $rodadaNumero - 1,
            'rodada_seguinte' => $rodadaNumero + 1
        ];

        return view('minhas_previsoes', $data);
    }

    public function enviar_previsao(Request $request)
    {
        $model = new Futebol_model;

        $user_id = session()->get('userId');

        $partidaId = $request->input('partida_id');
        $placarMandante = $request->input('placar_mandante');
        $placarVisitante = $request->input('placar_visitante');
        
        $previsaoByRodada = $model->getPrevisoesByIdPartidaAndUser($user_id, $partidaId);
        
        $partida = $model->getPartidas($partidaId);
        $rodada = $partida[0]->rodada;

        if($partida[0]->status != 'finalizado') {
            if(empty($previsaoByRodada)) {
                $model->insertPrevisao($partidaId, $placarMandante, $placarVisitante, $rodada, $user_id, 'aguardando');
                return response()->json(['message' => 'Previsão enviada com sucesso', 'status' => 1]);
            } else {
                $model->updatePrevisaoUser($partidaId, $user_id, $placarMandante, $placarVisitante);
                return response()->json(['message' => 'Previsão atualizada com sucesso', 'status' => 1]);
            }
        } else {
            return response()->json(['message' => 'Essa partida não permite mais o envio de previsões', 'status' => 0]);
        }
    }
}
