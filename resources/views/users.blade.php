@extends('templates.layoutMain')
@section('conteudo')
    @php
        use Carbon\Carbon;
    @endphp
    <main class="content">
        <div class="content-title mb-4">
            <i class="icon icofont-users mr-2"></i>
            <div>
                <h1>Usuários</h1>
                <h2>Acompanhe os usuários da plataforma</h2>
            </div>
        </div>
        <table class="table table-bordered table-sriped table-hover mt-4">
            <thead>
                <th>Nome</th>
                <th>Email</th>
                <th>Celular</th>
                <th>Data de Criação</th>
                <th>Ações</th>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td><?= $user->admin ? '<i style="color:yellow; font-size:1.5rem;" class="icofont-star mr-2"></i>' : '' ?>{{ $user->name . ' ' . $user->lastname }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ '(' . substr($user->phone, 0, 2) . ') ' . substr($user->phone, 2, 5) . '-' . substr($user->phone, 7) }}</td>
                        <td>{{ Carbon::parse($user->created_at)->format('d/m/Y') }}</td>
                        <td>
                            <a href="../controllers/save_user.php?update={{ $user->id }}"
                                class="btn btn-warning rounded-circle mr-2" title="Editar">
                                <i class="icofont-edit"></i>
                            </a>
                            <a href="?delete={{ $user->id }}" class="btn btn-danger rounded-circle" title="Excluir">
                                <i class="icofont-trash"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </main>
@endsection
