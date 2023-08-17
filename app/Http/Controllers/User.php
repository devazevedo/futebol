<?php

namespace App\Http\Controllers;

use App\Models\Futebol_model;
use Illuminate\Http\Request;
use App\Models\Users;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class User extends Controller
{
    public function index()
    {
        // $users = new Users;
        // $hashedPassword = Hash::make('a');
        // $users->updatePassword(2, $hashedPassword);

        if (session()->has('userId')) {
            $futebol = new Futebol_model;
            $users = new Users;

            $user_id = session()->get('userId');

            $user = $users->getUsers($user_id);
            $campeonato = $futebol->getCampeonatoById(10);
            $previsoesRodadaAtual = $futebol->getPrevisoes($user_id, null, $campeonato[0]->rodada_atual);
            $previsaoByUser = $futebol->getPrevisoes($user_id);

            if(!empty($previsaoByUser) && $previsaoByUser[0]->status != 'aguardando') {
                $previsoes_certas = 0;
                $previsoes_erradas = 0;
                $previsoes_parcial = 0;
                
                foreach ($previsaoByUser as $previsao) {
                    switch ($previsao->status) {
                        case 'certo':
                            $previsoes_certas += 1;
                            break;
                        case 'errado':
                            $previsoes_erradas += 1;
                            break;
                        case 'parcial':
                            $previsoes_parcial += 1;
                            break;
                        
                        default:
                            
                            break;
                    }
                }
    
                $total_previsoes = $previsoes_certas + $previsoes_erradas + $previsoes_parcial;
    
                $porcentagem_certas = ($previsoes_certas / $total_previsoes) * 100;
                $porcentagem_erradas = ($previsoes_erradas / $total_previsoes) * 100;
                $porcentagem_parcial = (($previsoes_parcial / $total_previsoes) * 100) / 2;
    
                $porcentagem_acertos = ($porcentagem_certas + $porcentagem_parcial);
    
                $pontuacao = 0;
    
                foreach ($previsoesRodadaAtual as $previsao) {
                    if($previsao->status === 'parcial') {
                        $pontuacao += 1;
                    } else if ($previsao->status === 'certo') {
                        $pontuacao += 3;
                    }
                }
            } else {
                $pontuacao = 0;
                $porcentagem_acertos = 0;
            }

            $data = [
                'pontuacao_ao_vivo' => $pontuacao,
                'porcentagem_acertos' => $porcentagem_acertos,
                'saldo' => $user[0]->saldo
            ];

            return view('home', $data);
        } else {
            $data = [
                'show' => true,
                'message' => "Sua sessão foi expirada, faça seu login e acesse a plataforma novamente.",
            ];
            return view('login', $data);
        }
    }

    public function login()
    {
        return view('login');
    }

    public function logar(Request $request)
    {

        $model = new Users;
        $futebol = new Futebol_model;

        $request->validate(
            [
                'email' => 'required',
                'password' => 'required'
            ],
            [
                'email.required' => 'Email é um campo obrigatório!',
                'password.required' => 'Senha é um campo obrigatório!'
            ]
        );

        $email = $request->input('email');
        $password = $request->input('password');

        $user = $model->getUsers(null, $email);

        if (!empty($user)) {

            $passwordUser = $user[0]->password;

            if (Hash::check($password, $passwordUser)) {
                session()->put([
                    'userId' => $user[0]->id,
                    'name' => $user[0]->name,
                    'lastname' => $user[0]->lastname,
                    'email' => $user[0]->email,
                    'cpf' => $user[0]->cpf,
                    'phone' => $user[0]->phone,
                    'isAdmin' => $user[0]->admin,
                    'profileImg' => $user[0]->imagem,
                    'saldo' => $user[0]->saldo,
                    'previsao_paga' => $user[0]->previsao_paga
                ]);
                

                $campeonato = $futebol->getCampeonatoById(10);
                $previsoesRodadaAtual = $futebol->getPrevisoes($user[0]->id, null, $campeonato[0]->rodada_atual);
                $previsaoByUser = $futebol->getPrevisoes($user[0]->id);

                if(!empty($previsaoByUser) && $previsaoByUser[0]->status != 'aguardando') {
                    $previsoes_certas = 0;
                    $previsoes_erradas = 0;
                    $previsoes_parcial = 0;
                    
                    foreach ($previsaoByUser as $previsao) {
                        switch ($previsao->status) {
                            case 'certo':
                                $previsoes_certas += 1;
                                break;
                            case 'errado':
                                $previsoes_erradas += 1;
                                break;
                            case 'parcial':
                                $previsoes_parcial += 1;
                                break;
                            
                            default:
                                
                                break;
                        }
                    }
        
                    $total_previsoes = $previsoes_certas + $previsoes_erradas + $previsoes_parcial;
        
                    $porcentagem_certas = ($previsoes_certas / $total_previsoes) * 100;
                    $porcentagem_erradas = ($previsoes_erradas / $total_previsoes) * 100;
                    $porcentagem_parcial = (($previsoes_parcial / $total_previsoes) * 100) / 2;
        
                    $porcentagem_acertos = ($porcentagem_certas + $porcentagem_parcial);
        
                    $pontuacao = 0;
        
                    foreach ($previsoesRodadaAtual as $previsao) {
                        if($previsao->status === 'parcial') {
                            $pontuacao += 1;
                        } else if ($previsao->status === 'certo') {
                            $pontuacao += 3;
                        }
                    }
                } else {
                    $pontuacao = 0;
                    $porcentagem_acertos = 0;
                }

                $data = [
                    'pontuacao_ao_vivo' => $pontuacao,
                    'porcentagem_acertos' => $porcentagem_acertos,
                    'saldo' => $user[0]->saldo
                ];


                return view('home', $data);
            } else {
                echo 'deu ruim';
            }
        } else {
            echo 'Erro';
        }
    }

    public function users()
    {
        if(session()->get('isAdmin') === 0) {
            return view('home');
        }

        $model = new Users;

        $users = $model->getUsers();

        $data = [
            'users' => $users
        ];

        return view('users', $data);
    }

    public function register()
    {
        return view('register');
    }

    public function register_user(Request $request)
    {

        $model = new Users;

        $request->validate(
            [
                'name' => 'required',
                'lastname' => 'required',
                'email' => 'required',
                'emailConfirm' => 'required',
                'password' => 'required',
                'passwordConfirm' => 'required',
                'phone' => 'required',
                'password' => [
                    'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#])[A-Za-z\d@$!%*?&#]{8,}$/',
                ]
            ],
            [
                'name.required' => 'Nome é um campo obrigatório!',
                'lastname.required' => 'Sobrenome é um campo obrigatório!',
                'email.required' => 'Email é um campo obrigatório!',
                'emailConfirm.required' => 'Confirmação do email é um campo obrigatório!',
                'password.required' => 'Senha é um campo obrigatório!',
                'password.regex' => 'A senha deve conter pelo menos 8 caracteres, incluindo pelo menos uma letra maiúscula, uma letra minúscula, um número e um caractere especial!',
                'passwordConfirm.required' => 'Confirmação de senha é um campo obrigatório!',
                'phone.required' => 'Celular é um campo obrigatório!',
            ]
        );

        $name = $request->input('name');
        $lastname = $request->input('lastname');
        $email = $request->input('email');
        $emailConfirm = $request->input('emailConfirm');
        $password = $request->input('password');
        $passwordConfirm = $request->input('passwordConfirm');
        $phone = $request->input('phone');

        if ($email !== $emailConfirm) {
            $data = [
                'status' => 0,
                'message' => 'Os emails informados não conferem!',
                'name' => $name,
                'lastname' => $lastname,
                'email' => $email,
                'phone' => $phone
            ];
            return view('register', $data);
        }

        if ($password !== $passwordConfirm) {
            $data = [
                'status' => 0,
                'message' => 'As senhas informadas não conferem!',
                'name' => $name,
                'lastname' => $lastname,
                'email' => $email,
                'phone' => $phone
            ];
            return view('register', $data);
        }

        $user = $model->getUsers(null, $email);

        if (!empty($user)) {
            $data = [
                'status' => 0,
                'message' => 'Já existe um cadastro para esse email!',
                'name' => $name,
                'lastname' => $lastname,
                'email' => $email,
                'phone' => $phone
            ];
            return view('register', $data);
        }

        $hashedPassword = Hash::make($password);

        $model->insertUser($name, $lastname, $email, $hashedPassword, $phone);

        return view('login');
    }

    public function logout()
    {
        session()->forget([
            'userId',
            'name',
            'lastname',
            'email',
            'phone',
            'isAdmin'
        ]);

        return view('login');
    }

    public function profile(Request $request)
    {
        // echo $request->input('previsao_paga');
        // exit;
        $users = new Users;
        $user_id = session()->get('userId');
        $user = $users->getUsers($user_id);

        if($request->isMethod('post')) {
            $request->validate(
                [
                    'email' => 'required',
                    'celular' => 'required',
                ]
            );

            $email = $request->input('email');
            $phone = preg_replace('/\D/', '', $request->input('celular'));
            if(!empty($request->file('image')) && $user[0]->imagem !== $request->file('image')) {
                if ($user[0]->imagem) {
                    Storage::disk('public')->delete($user[0]->imagem);
                }
                $imagePath = $request->file('image')->store('img', 'public');
                session()->put('profileImg', $imagePath);
            } else {
                $imagePath = $user[0]->imagem;
            }

            if($email !== $user[0]->email || $phone !== $user[0]->phone || !empty($request->file('image')) || $request->input('previsao_paga') != $user[0]->previsao_paga) {
                $users->updateUser($user_id, $email, $phone, $imagePath, $request->input('previsao_paga'));
                session()->put([
                    'userId' => $user_id,
                    'email' => $email,
                    'phone' => $phone,
                    'profileImg' => $imagePath,
                    'previsao_paga' => $request->input('previsao_paga')
                ]);
                Session::flash('success', 'Informações atualizadas com sucesso!');
            }

            $user = $users->getUsers($user_id);

            if($request->input('currentPassword')) {
                $request->validate(
                    [
                        'newPassword' => [
                            'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#])[A-Za-z\d@$!%*?&#]{8,}$/',
                        ]
                    ],
                    [
                        'newPassword.regex' => Session::flash('error', 'A senha deve conter pelo menos 8 caracteres, incluindo pelo menos uma letra maiúscula, uma letra minúscula, um número e um caractere especial!'),
                    ]
                );

                if(Hash::check($request->input('currentPassword'), $user[0]->password)) {
                    if ($request->input('newPassword') !== $request->input('confirmNewPassword')) {
                        Session::flash('error', 'As senhas informadas não conferem!');

                        return view('profile');
                    } else {
                        $hashedPassword = Hash::make($request->input('newPassword'));
                        $users->updatePassword($user_id, $hashedPassword);
                        Session::flash('success', 'Informações atualizadas com sucesso!');
                    }
                } else {
                    Session::flash('error', 'A senha informada está incorreta');

                    return view('profile');
                }
            }

        }

        $user = $users->getUsers($user_id);

        $transacoes = $users->getTransacoes($user_id);
        
        $token = env('TOKEN_PAG_SEGURO');

        foreach ($transacoes as $transacao) {
            if(!empty($transacao->consultar_checkout)) {
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $token,
                    'Content-Type' => 'application/json',
                ])->get($transacao->consultar_checkout);
                
                $responseData = json_decode($response->getBody(), true); // Convertendo o JSON para um array associativo

                
                if(!empty($responseData['orders'])) {
                    $response = Http::withHeaders([
                        'Authorization' => 'Bearer ' . $token,
                        'Content-Type' => 'application/json',
                    ])->get($responseData['orders'][0]['links'][0]['href']);

                    $responseData = json_decode($response->getBody(), true); // Convertendo o JSON para um array associativo

                    if($responseData['charges'][0]['payment_response']['code'] == 20000) {
                        $recargaSaldo = $responseData['charges'][0]['amount']['value'];
                        $saldoNovo = ($recargaSaldo / 100) + $user[0]->saldo;
                        session()->put('saldo', $saldoNovo);
                        $users->updateSaldo($user[0]->id, $saldoNovo);
                        $users->updateStatusTransacao($responseData['charges'][0]['reference_id'], 'pago');
                    }
                } else {
                    $response = Http::withHeaders([
                        'Authorization' => 'Bearer ' . $token,
                        'Content-Type' => 'application/json',
                    ])->post($responseData['links'][2]['href']);

                    $responseData2 = json_decode($response->getBody(), true); // Convertendo o JSON para um array associativo

                    if($responseData2['status'] == 'INACTIVE') {
                        $users->updateStatusTransacao($responseData['reference_id'], 'inativo');
                    }
                }
            }
        }



        return view('profile');
    }

    // public function register_partners(Request $request)
    // {
    //     $request->validate(
    //         [
    //             'name' => 'required',
    //             'phone' => 'required'
    //         ],
    //         [
    //             'name.required' => 'Nome é um campo obrigatório!',
    //             'phone.required' => 'Telefone é um campo obrigatório!'
    //         ]
    //     );
    //     $name = $request->input('name');
    //     $phone = $request->input('phone'); //se eu adicionar outro parametro seria o valor default

    //     $model = new Socios;
    //     $partners = $model->getPartners();
    //     $model->insertPartners($name, $phone);

    //     $data = [
    //         'show' => true,
    //         'message' => "Número de sócios: " . count($partners),
    //         'partners' => $partners
    //     ];

    //     return view('home', $data);
    // }

    // public function session(Request $request)
    // {
    //     //criando sessao
    //     $request->session()->put([
    //         'usuario' => 'admin',
    //         'role' => 'admin'
    //     ]);

    //     //apresentando sessao
    //     echo $request->session()->get('usuario', 'default');

    //     //limpando sessao
    //     $request->session()->forget(['usuario', 'role']); //string ou array

    //     //global
    //     session()->put('usuario', 'admin');

    //     //flash data
    //     session()->flash('message', 'Dados atualizados com sucesso!'); //essa informação so vai aparecer no proximo request, apos um refresh ele limpa
    // }
}
