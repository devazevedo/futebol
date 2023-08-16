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

    public function insertUser($name, $lastname, $email, $hashedPassword, $phone) 
    {
        DB::insert("INSERT INTO users VALUES(0, ?, ?, ?, ?, ?, 0, NOW(), NOW(), 0, null)", ["$email", "$hashedPassword", "$name", "$lastname", "$phone"]);
    }

    public function updateUser($id, $email, $celular, $imagePath)
    {
        $query = "UPDATE users 
                  SET email = '$email',
                      phone = '$celular',
                      imagem = '$imagePath'
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
}