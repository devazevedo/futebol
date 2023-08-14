<?php

namespace App\models;

use Illuminate\Support\Facades\DB;

class Futebol_model
{
    public function getCampeonatos()
    {
        return DB::select("SELECT * FROM campeonatos");
    }

    public function getCampeonatoById($id)
    {
        return DB::select("SELECT * FROM campeonatos WHERE id = ?", [$id]);
    }

    public function getTimeById($id)
    {
        return DB::select("SELECT * FROM times WHERE id = ?", [$id]);
    }

    public function insertTime($name, $id, $sigla, $escudo)
    {
        DB::insert("INSERT INTO times VALUES(?, ?, ?, ?)", [$id, $name, $sigla, $escudo]);
    }

    public function getTimesInseridosCampeonato($id_campeonato, $id_time)
    {
        return DB::select("SELECT * FROM classificacao_times WHERE campeonato_id = ? AND time_id = ? ORDER BY posicao ASC", [$id_campeonato, $id_time]);
    }

    public function getClassificacaoTimesByIdCampeonato($id_campeonato)
    {
        return DB::select("SELECT * FROM classificacao_times INNER JOIN times ON times.id = classificacao_times.time_id WHERE campeonato_id = ? ORDER BY posicao ASC", [$id_campeonato]);
    }

    public function insertTimeTabelaClassificacao($id_time, $id_campeonato, $posicao, $pontos, $jogos, $vitorias, $empates, $derrotas, $gols_pro, $gols_contra, $saldo_gols, $aproveitamento, $variacao_posicao, $ultimos_jogos)
    {
        $query = "INSERT INTO classificacao_times VALUES(0, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $values = [
            $id_time,
            $id_campeonato,
            $posicao,
            $pontos,
            $jogos,
            $vitorias,
            $empates,
            $derrotas,
            $gols_pro,
            $gols_contra,
            $saldo_gols,
            $aproveitamento,
            $variacao_posicao,
            $ultimos_jogos
        ];

        DB::insert($query, $values);
    }

    public function updateTimeTabelaClassificacao($id_time, $id_campeonato, $posicao, $pontos, $jogos, $vitorias, $empates, $derrotas, $gols_pro, $gols_contra, $saldo_gols, $aproveitamento, $variacao_posicao, $ultimos_jogos)
    {
        $query = "UPDATE classificacao_times 
                  SET posicao = ?,
                      pontos = ?, 
                      jogos = ?, 
                      vitorias = ?, 
                      empates = ?, 
                      derrotas = ?, 
                      gols_pro = ?, 
                      gols_contra = ?, 
                      saldo_gols = ?, 
                      aproveitamento = ?, 
                      variacao_posicao = ?, 
                      ultimos_jogos = ? 
                  WHERE time_id = ? AND campeonato_id = ?";

        $values = [
            $posicao,
            $pontos,
            $jogos,
            $vitorias,
            $empates,
            $derrotas,
            $gols_pro,
            $gols_contra,
            $saldo_gols,
            $aproveitamento,
            $variacao_posicao,
            $ultimos_jogos,
            $id_time,
            $id_campeonato
        ];

        DB::update($query, $values);
    }

    public function insertCampeonato($campeonato_id, $name, $popular_name, $rodada_atual, $season, $round, $phase, $status, $logo)
    {
        $query = "INSERT INTO campeonatos VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $values = [
            $campeonato_id,
            $name,
            $popular_name,
            $rodada_atual,
            $season,
            $round,
            $phase,
            $status,
            $logo
        ];

        DB::insert($query, $values);
    }

    public function insertPartida($partida_id, $campeonato_id, $id_time_mandante, $id_time_visitante, $rodada, $estadio, $data_realizacao, $hora_realizacao, $status)
    {
        $query = "INSERT INTO partidas VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $values = [
            $partida_id,
            $campeonato_id,
            $id_time_mandante,
            $id_time_visitante,
            $rodada,
            $estadio,
            $data_realizacao,
            $hora_realizacao,
            $status
        ];

        DB::insert($query, $values);
    }

    public function insertEstatisticas($partida_id, $id_time, $placar, $posse_bola, $escanteios, $impedimentos, $faltas, $passes_total, $passes_completos, $passes_errados, $passes_precisao, $finalizacao_total, $finalizacao_no_gol, $finalizacao_pra_fora, $finalizacao_na_trave, $finalizacao_bloqueado, $finalizacao_precisao, $amarelos, $vermelhos, $defesas, $desarmes, $esquema_tatico)
    {
        $query = "INSERT INTO estatisticas VALUES(0, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $values = [
            $partida_id,
            $id_time,
            $placar,
            $posse_bola,
            $escanteios,
            $impedimentos,
            $faltas,
            $passes_total,
            $passes_completos,
            $passes_errados,
            $passes_precisao,
            $finalizacao_total,
            $finalizacao_no_gol,
            $finalizacao_pra_fora,
            $finalizacao_na_trave,
            $finalizacao_bloqueado,
            $finalizacao_precisao,
            $amarelos,
            $vermelhos,
            $defesas,
            $desarmes,
            $esquema_tatico
        ];

        DB::insert($query, $values);
    }

    public function updatePartida($partida_id, $campeonato_id, $id_time_mandante, $id_time_visitante, $rodada, $data_realizacao, $hora_realizacao, $status)
    {
        $query = "UPDATE partidas 
                  SET id_time_mandante = ?,
                      id_time_visitante = ?, 
                      rodada = ?, 
                      data_realizacao = ?, 
                      hora_realizacao = ?, 
                      status = ?
                  WHERE id = ? AND campeonato_id = ?";

        $values = [
            $id_time_mandante,
            $id_time_visitante,
            $rodada,
            $data_realizacao,
            $hora_realizacao,
            $status,
            $partida_id,
            $campeonato_id
        ];

        DB::update($query, $values);
    }

    public function updateEstadioPartida($id, $estadio)
    {
        DB::update("UPDATE partidas SET estadio = ? WHERE id = ?", [$estadio, $id]);
    }

    public function getPartidasRodada($rodada = null)
    {
        $query = "SELECT 
                p.*, 
                em.placar AS mandante_placar,
                ev.placar AS visitante_placar,
                tm.name AS time_mandante_nome, 
                tm.escudo AS time_mandante_escudo, 
                tv.name AS time_visitante_nome, 
                tv.escudo AS time_visitante_escudo
            FROM partidas p
                INNER JOIN times tm ON p.id_time_mandante = tm.id
                INNER JOIN times tv ON p.id_time_visitante = tv.id
                INNER JOIN campeonatos c ON p.campeonato_id = c.id";

        $query .= " LEFT JOIN estatisticas em ON p.id = em.partida_id AND p.id_time_mandante = em.time_id";
        $query .= " LEFT JOIN estatisticas ev ON p.id = ev.partida_id AND p.id_time_visitante = ev.time_id";

        if ($rodada !== null) {
            $query .= " WHERE p.rodada = :rodada";
        } else {
            $query .= " WHERE c.rodada_atual = p.rodada";
        }

        $query .= " ORDER BY STR_TO_DATE(p.data_realizacao, '%d/%m/%Y')
                LIMIT 10";

        if ($rodada !== null) {
            return DB::select($query, ['rodada' => $rodada]);
        } else {
            return DB::select($query);
        }
    }

    public function getPartidas($id = null)
    {
        $query = "SELECT * FROM partidas";

        if ($id !== null) {
            $query .= " WHERE id = $id";
        }

        return DB::select($query);
    }

    public function getEstatisticaByIdPartida($partida_id, $id_time = null)
    {
        $query = "SELECT * FROM estatisticas WHERE partida_id = $partida_id";

        if ($id_time !== null) {
            $query .= " AND time_id = $id_time";
        }

        return DB::select($query);
    }

    public function getPrevisoesByUserIdAndRodada($user_id, $rodada)
    {
        return DB::select("SELECT * FROM previsoes WHERE user_id = ? AND rodada = ?", [$user_id, $rodada]);
    }

    public function getPrevisoesByIdPartida($partida_id)
    {
        return DB::select("SELECT * FROM previsoes WHERE partida_id = ?", [$partida_id]);
    }

    public function updatePrevisao($id, $status) 
    {
        return DB::update("UPDATE previsoes SET status = $status WHERE id = $id");
    }

    public function updatePrevisaoUser($partidaId, $user_id, $placarMandante, $placarVisitante) 
    {
        return DB::update("UPDATE previsoes SET placar_mandante = $placarMandante, placar_visitante = $placarVisitante WHERE partida_id = $partidaId AND user_id = $user_id");
    }

    public function getJogadorById($jogadorId)
    {
        return DB::select("SELECT * FROM jogadores WHERE id = ?", [$jogadorId]);
    }

    public function insertJogadores($id, $nome, $camisa, $posicao, $sigla)
    {
        $query = "INSERT INTO jogadores VALUES(?, ?, ?, ?, ?)";
        $values = [
            $id,
            $nome,
            $camisa,
            $posicao,
            $sigla
        ];

        DB::insert($query, $values);
    }

    public function insertEscalacaoPartida($partida_id, $idJogador, $idTreinador, $ordem, $tipo, $time)
    {
        $query = "INSERT INTO escalacao_por_partida VALUES(0, ?, ?, ?, ?, ?, ?)";
        $values = [
            $partida_id,
            $idJogador,
            $idTreinador,
            $ordem,
            $tipo,
            $time
        ];

        DB::insert($query, $values);
    }

    public function insertGols($partida_id, $jogador_id, $minuto, $periodo, $penalti, $contra)
    {
        $query = "INSERT INTO gols VALUES(0, ?, ?, ?, ?, ?, ?)";
        $values = [
            $partida_id,
            $jogador_id,
            $minuto,
            $periodo,
            $penalti,
            $contra
        ];

        DB::insert($query, $values);
    }

    public function insertSubstituicoes($partida_id, $id_saiu, $id_entrou, $periodo, $minuto)
    {
        $query = "INSERT INTO substituicoes VALUES(0, ?, ?, ?, ?, ?)";
        $values = [
            $partida_id,
            $id_saiu,
            $id_entrou,
            $periodo,
            $minuto
        ];

        DB::insert($query, $values);
    }

    public function insertTreinadores($id, $nome)
    {
        $query = "INSERT INTO treinadores VALUES(?, ?)";
        $values = [
            $id,
            $nome
        ];

        DB::insert($query, $values);
    }

    public function insertPrevisao($partidaId, $placarMandante, $placarVisitante, $rodada, $user_id, $status)
    {
        $query = "INSERT INTO previsoes VALUES(0, ?, ?, ?, ?, ?, ?)";
        $values = [
            $placarMandante,
            $placarVisitante,
            $rodada,
            $partidaId,
            $user_id,
            $status
        ];

        DB::insert($query, $values);
    }

    public function getTreinadorById($id)
    {
        return DB::select("SELECT * FROM treinadores WHERE id = ?", [$id]);
    }

    public function getPrevisoesByIdPartidaAndUser($user_id, $partida_id)
    {
        return DB::select("SELECT * FROM previsoes WHERE user_id = ? AND partida_id = ?", [$user_id, $partida_id]);
    }

    public function getStatusPartida($partida_id)
    {
        return DB::select("SELECT status FROM partidas WHERE id = ?", [$partida_id]);
    }

}
