@extends('templates.layoutMain')
@section('style')
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection
@section('conteudo')
    @php
        use Carbon\Carbon;
    @endphp
    <main class="content">
        @if (Session::has('success'))
            <div class="alert alert-success">
                {{ Session::get('success') }}
            </div>
        @endif
        @if (Session::has('error'))
            <div class="alert alert-danger">
                {{ Session::get('error') }}
            </div>
        @endif
        <div class="content-title mb-4">
            <i class="icon icofont-user mr-2"></i>
            <div>
                <h1>Perfil</h1>
                <h2>Visualize ou edite suas informações</h2>
            </div>
        </div>
        <div class="content-body col-lg-12">
            <form action="{{ route('profile') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="d-flex">
                    <div class="col-lg-4">
                        <div class="form-group col-lg-12" style="display: flex; align-items: center; flex-direction: column;">
                            <label for="image">Imagem</label>
                            @if ($imagem)
                                <img id="previewImage" src="{{ asset($imagem) }}" alt="Imagem do perfil"
                                    class="profile-image">
                            @else
                                <img id="previewImage" src="{{ asset('img/user.png') }}" alt="Imagem do perfil"
                                    class="profile-image">
                            @endif
                            <input type="file" id="image" name="image" accept="image/*" class="form-control">
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group col-lg-12">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" class="form-control"
                                required
                                value="{{ $email }}" placeholder="Informe seu email">
                        </div>
                        <div class="form-group col-lg-12">
                            <label for="celular">Celular</label>
                            <input type="text" id="celular" name="celular" class="form-control"
                                required
                                value="{{ $celular }}" placeholder="Informe seu celular">
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group col-lg-12">
                            <label for="currentPassword">Senha Atual</label>
                            <input type="password" id="currentPassword" name="currentPassword" class="form-control"
                                placeholder="Informe sua senha">
                        </div>
                        <div class="form-group col-lg-12">
                            <label for="newPassword">Nova Senha</label>
                            <input type="password" id="newPassword" name="newPassword" class="form-control"
                                {{-- pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#])[A-Za-z\d@$!%*?&#]{8,}$" --}}
                                title="A senha deve conter pelo menos 8 caracteres, incluindo pelo menos uma letra maiúscula, uma letra minúscula, um número e um caractere especial"
                                placeholder="Informe sua nova senha">
                        </div>
                        <div class="form-group col-lg-12">
                            <label for="confirmNewPassword">Confirmação Nova Senha</label>
                            <input type="password" id="confirmNewPassword" name="confirmNewPassword" class="form-control"
                                placeholder="Confirme sua nova senha">
                        </div>
                        <div class="form-group col-lg-12 mt-2" style="display: flex; justify-content: flex-end;">
                            <input class="btn btn-success" type="submit" value="Salvar">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </main>
@endsection
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

<script>
    window.onload = (event) => {
        $(document).ready(function() {
            $('#celular').mask('(00) 00000-0000');
        });

        $(document).ready(function() {
            setTimeout(() => {
                $('.alert').addClass('d-none')
            }, 5000);
        });

        $(document).ready(function() {
            $('#image').change(function() {
                var input = this;
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $('#previewImage').attr('src', e.target.result);
                    };
                    reader.readAsDataURL(input.files[0]);
                }
            });
        });
    };
</script>
