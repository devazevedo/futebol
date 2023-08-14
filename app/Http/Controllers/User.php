<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Users;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class User extends Controller
{
    public function index()
    {

        if (session()->has('userId')) {
            // trata o programa tendo usuario logado
            return view('home');
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

        $user = $model->getUserByEmail($email);

        if (!empty($user)) {

            $passwordUser = $user[0]->password;

            if (Hash::check($password, $passwordUser)) {
                session()->put([
                    'userId' => $user[0]->id,
                    'name' => $user[0]->name,
                    'lastname' => $user[0]->lastname,
                    'email' => $user[0]->email,
                    'phone' => $user[0]->phone,
                    'isAdmin' => $user[0]->admin
                ]);

                return view('home');
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
                'phone' => 'required'
            ],
            [
                'name.required' => 'Nome é um campo obrigatório!',
                'lastname.required' => 'Sobrenome é um campo obrigatório!',
                'email.required' => 'Email é um campo obrigatório!',
                'emailConfirm.required' => 'Confirmação do email é um campo obrigatório!',
                'password.required' => 'Senha é um campo obrigatório!',
                'passwordConfirm.required' => 'Confirmação de senha é um campo obrigatório!',
                'phone.required' => 'Celular é um campo obrigatório!'
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

        $user = $model->getUserByEmail($email);

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
