@extends('templates.layoutMain')
@section('conteudo')
    @php
        use Carbon\Carbon;
    @endphp
    <main class="content">
        <div class="content-title mb-4">
            <i class="icon icofont-code mr-2"></i>
            <div>
                <h1>Api's</h1>
                <h2>Monitore as api's da sua plataforma</h2>
            </div>
        </div>
        <table class="table table-bordered table-sriped table-hover mt-4">
            <thead>
                <th>Nome</th>
                <th>URL</th>
                <th>Ultima execução</th>
                <th>Ações</th>
            </thead>
            <tbody>
                @if (!empty($apis))
                    @foreach ($apis as $api)
                        <tr>
                            <td>{{ $api->name }}</td>
                            <td>{{ $api->url }}</td>
                            <td>{{ $api->last_execute ? Carbon::parse($api->last_execute)->format('d/m/Y') : ' - ' }}</td>
                            <td>
                                <a href="{{ route('execute_api', ['id' => $api->id]) }}" class="btn btn-primary rounded-circle" title="Executar">
                                    <i class="icofont-ui-check"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td style="text-align: center;" colspan="4">Nenuma api cadastrada, contate o suporte</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </main>
@endsection
