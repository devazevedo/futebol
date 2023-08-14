@extends('templates.layoutMain')
@section('conteudo')
    <div>
        <h1>{{'Seja bem vindo(a)'. session()->get('name') . ' ' . session()->get('lastname') }}</h1>
    </div>
@endsection