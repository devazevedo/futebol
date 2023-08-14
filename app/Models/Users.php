<?php

namespace App\models;

use Illuminate\Support\Facades\DB;

class Users
{
    public function getUsers() {
        return DB::select("SELECT * FROM users");
    }

    public function insertUser($name, $lastname, $email, $hashedPassword, $phone) {
        DB::insert("INSERT INTO users VALUES(0, ?, ?, ?, ?, ?, 0, NOW(), NOW())", [$email, $hashedPassword, $name, $lastname, $phone]);
    }

    public function getUserByEmail($email) {
        return DB::select("SELECT * FROM users WHERE email = ?", [$email]);
    }
}