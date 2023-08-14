<?php

namespace App\models;

use Illuminate\Support\Facades\DB;

class Apis
{
    public function getApis() {
        return DB::select("SELECT * FROM apis");
    }

    public function getApiById($id) {
        return DB::select("SELECT * FROM apis WHERE id = ?", [$id]);
    }

    public function update($id) {
        DB::update("UPDATE apis SET last_execute = ? WHERE id = ?", [NOW(), $id]);
    }

    // public function insertUser($name, $lastname, $email, $hashedPassword, $phone) {
    //     DB::insert("INSERT INTO users VALUES(0, ?, ?, ?, ?, ?, 0, NOW(), NOW())", [$email, $hashedPassword, $name, $lastname, $phone]);
    // }
}