<?php

namespace App\models;

use Illuminate\Support\Facades\DB;

class Users
{
    public function getUsers($id = null, $email = null) 
    {
        $query = "SELECT * FROM users WHERE 1 = 1";

        if(!empty($id)) {
            $query .= " AND id = $id";
        }

        if(!empty($email)) {
            $query .= " AND email = '$email'";
        }

        return DB::select($query);
    }

    public function insertUser($name, $lastname, $email, $hashedPassword, $phone, $cpf) 
    {
        DB::insert("INSERT INTO users VALUES(0, ?, ?, ?, ?, ?, ?, 0, 0, 0, NOW(), NOW(), null)", ["$email", "$hashedPassword", "$name", "$lastname", "$cpf", "$phone"]);
    }

    public function updateUser($id, $email, $celular, $imagePath, $previsao_paga)
    {
        $query = "UPDATE users 
                  SET email = '$email',
                      phone = '$celular',
                      imagem = '$imagePath',
                      previsao_paga = $previsao_paga
                  WHERE id = $id";

        DB::update($query);
    }

    public function updatePassword($user_id, $hashedPassword)
    {
        $query = "UPDATE users
                  SET password = '$hashedPassword'
                  WHERE id = $user_id";

        DB::update($query);
    }

    public function insertTransacao($user_id, $valor, $status)
    {
        $id = DB::table('transacoes')->insertGetId([
            'user_id' => $user_id,
            'valor' => $valor,
            'status' => $status,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return $id;
    }

    public function updateTransacao($id, $urlConsulta)
    {
        $query = "UPDATE transacoes
                  SET consultar_checkout = '$urlConsulta'
                  WHERE id = $id";

        DB::update($query);
    }

    public function updateStatusTransacao($id, $status)
    {
        $query = "UPDATE transacoes
                  SET status = '$status'
                  WHERE id = $id";

        DB::update($query);
    }

    public function getTransacoes($user_id)
    {
        return DB::select("SELECT * FROM transacoes WHERE user_id = $user_id AND status = 'pendente'");
    }

    public function updateSaldo($user_id, $saldoNovo){
        $query = "UPDATE users SET saldo = $saldoNovo WHERE id = $user_id";

        DB::update($query);
    }
}