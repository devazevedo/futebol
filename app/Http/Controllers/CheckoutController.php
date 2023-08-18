<?php

namespace App\Http\Controllers;

use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Futebol_model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class CheckoutController extends Controller
{
    public function criarCheckout(Request $request)
    {
        $url = 'https://sandbox.api.pagseguro.com/checkouts/';
        $token = env('TOKEN_PAG_SEGURO');

        $model = new Users;

        $reference_id_transaction = $model->insertTransacao(session()->get('userId'), $request->input('unit_amount'), 'pendente');

        $phone = session()->get('phone');
        $ddd = substr($phone, 0, 2);
        $number = substr($phone, 2);

        $params = [
            'reference_id' => $reference_id_transaction,
            'customer' => [
                'name' => session()->get('name'). ' '. session()->get('lastname'),
                'email' => session()->get('email'),
                'tax_id' => session()->get('cpf'),
                'phone' => [
                    'country' => '+55',
                    'area' => $ddd,
                    'number' => $number,
                ],
            ],
            'items' => [
                [
                    'reference_id' => $request->input('reference_id'),
                    'name' => $request->input('name_item'),
                    'quantity' => $request->input('quantity'),
                    'unit_amount' => $request->input('unit_amount'), // Valor em centavos (R$10,00)
                ],
            ],
            'additional_amount' => 0,
            'discount_amount' => 0,
        ];

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/json',
        ])->post($url, $params);

        $responseData = json_decode($response->getBody(), true); // Convertendo o JSON para um array associativo

        if (isset($responseData['links'][1]['href'])) {
            $pagSeguroCheckoutLink = $responseData['links'][1]['href'];
            $model->updateTransacao($reference_id_transaction, $responseData['links'][0]['href']);
            // Redireciona o usuário para a URL do PagSeguro em uma nova janela usando JavaScript
            echo "<script>window.open('$pagSeguroCheckoutLink', '_blank');</script>";
            return view('profile');
        } else {
            // Lógica de tratamento de erro ou outra ação em caso de falha
        }
    }
}
