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
                            @if (session()->get('profileImg'))
                                <img id="previewImage" src="{{ asset(session()->get('profileImg')) }}" alt="Imagem do perfil"
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
                            <input disabled type="email" id="email" name="email" class="form-control"
                                required
                                value="{{ session()->get('email') }}" placeholder="Informe seu email">
                        </div>
                        <div class="form-group col-lg-12">
                            <label for="cpf">CPF</label>
                            <input disabled type="text" id="cpf" name="cpf" class="form-control"
                                required
                                value="{{ session()->get('cpf') }}" placeholder="Informe seu email">
                        </div>
                        <div class="form-group col-lg-12">
                            <label for="celular">Celular</label>
                            <input type="text" id="celular" name="celular" class="form-control"
                                required
                                value="{{ session()->get('phone') }}" placeholder="Informe seu celular">
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group col-lg-12">
                            <label for="currentPassword">Senha Atual</label>
                            <div class="input-group">
                                <input type="password" id="currentPassword" name="currentPassword" class="form-control" autocomplete="off" placeholder="Informe sua senha">
                                <div class="input-group-append">
                                    <span class="input-group-text toggle-password" id="toggle-current-password">
                                        <i class="icofont-eye"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-lg-12">
                            <label for="newPassword">Nova Senha</label>
                            <div class="input-group">
                                <input type="password" id="newPassword" name="newPassword" class="form-control" autocomplete="off" pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#])[A-Za-z\d@$!%*?&#]{8,}$" title="A senha deve conter pelo menos 8 caracteres, incluindo pelo menos uma letra maiúscula, uma letra minúscula, um número e um caractere especial" placeholder="Informe sua nova senha">
                                <div class="input-group-append">
                                    <span class="input-group-text toggle-password" id="toggle-new-password">
                                        <i class="icofont-eye"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-lg-12">
                            <label for="confirmNewPassword">Confirmação Nova Senha</label>
                            <div class="input-group">
                                <input type="password" id="confirmNewPassword" name="confirmNewPassword" class="form-control" autocomplete="off" placeholder="Confirme sua nova senha">
                                <div class="input-group-append">
                                    <span class="input-group-text toggle-password" id="toggle-confirm-new-password">
                                        <i class="icofont-eye"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="content-title mt-4 mb-4">
                    <i class="icon icofont-money-bag mr-2"></i>
                    <div>
                        <h1>Saldo</h1>
                        <h2>Acompanhe seu saldo e suas configurações de previsões</h2>
                    </div>
                </div>
                <div class="d-flex">
                    <div class="col-lg-4 mt-4">
                        <div class="form-group col-lg-12">
                            <label for="previsao_paga">Previsão Paga</label>
                            <select class="form-control" name="previsao_paga" id="previsao_paga">
                                <option <?= session()->get('previsao_paga') == 1 ? '' : 'selected' ?> value="0">Não</option>
                                <option <?= session()->get('previsao_paga') == 1 ? 'selected' : '' ?> value="1">Sim</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-4 mt-4 d-flex">
                        <div class="form-group col-lg-12" style="display: flex; align-items: center; justify-content: center; margin: auto;">
                            <h1>R$ {{ number_format(session()->get('saldo'), 2, ',', '.') }}</h1>
                        </div>
                    </div>
                </div>
                <div class="form-group col-lg-12 mt-2" style="display: flex; justify-content: flex-end; position: absolute; top: 33vw;">
                    <input class="btn btn-success btn-lg" type="submit" value="Salvar">
                </div>
            </form>
            <div class="col-lg-12">
                <h1 style="text-align: center;" class="mb-4">Adicionar saldo:</h1>
                <div class="d-flex col-lg-4" style="justify-content: space-around; margin:auto;">
                    <form action="{{ route('criarCheckout') }}" method="POST">
                        @csrf
                        <input type="hidden" name="reference_id" value="1">
                        <input type="hidden" name="name_item" value="Recarga">
                        <input type="hidden" name="quantity" value="1">
                        <input type="hidden" name="unit_amount" value="1000">
                        <input class="btn btn-primary" type="submit" value="10,00">
                    </form>
                    <form action="{{ route('criarCheckout') }}" method="POST">
                        @csrf
                        <input type="hidden" name="reference_id" value="1">
                        <input type="hidden" name="name_item" value="Recarga">
                        <input type="hidden" name="quantity" value="1">
                        <input type="hidden" name="unit_amount" value="5000">
                        <input class="btn btn-primary" type="submit" value="50,00">
                    </form>
                    <form action="{{ route('criarCheckout') }}" method="POST">
                        @csrf
                        <input type="hidden" name="reference_id" value="1">
                        <input type="hidden" name="name_item" value="Recarga">
                        <input type="hidden" name="quantity" value="1">
                        <input type="hidden" name="unit_amount" value="10000">
                        <input class="btn btn-primary" type="submit" value="100,00">
                    </form>
                </div>
            </div>
        </div>
    </main>
@endsection
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

<script>

    @if(session()->has('pagSeguroCheckoutLink'))
            var pagSeguroCheckoutLink = '{{ session('pagSeguroCheckoutLink') }}';
            @php
                session()->forget(['pagSeguroCheckoutLink']);
            @endphp
            if (pagSeguroCheckoutLink) {
                window.open(pagSeguroCheckoutLink, '_blank');
            }
    @endif


    $(document).ready(function() {
        setTimeout(() => {
            console.log('entrou');
            $('.alert').addClass('d-none')
        }, 2500);
    });

    window.onload = (event) => {
        $(document).ready(function() {
            $('#celular').mask('(00) 00000-0000');
        });

        $(document).ready(function() {
            $('#cpf').mask('000.000.000-00', { reverse: true });
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

    document.addEventListener("DOMContentLoaded", function () {
        const currentPasswordInput = document.getElementById("currentPassword");
        const newPasswordInput = document.getElementById("newPassword");
        const confirmNewPasswordInput = document.getElementById("confirmNewPassword");
        const toggleCurrentPasswordButton = document.getElementById("toggle-current-password");
        const toggleNewPasswordButton = document.getElementById("toggle-new-password");
        const toggleConfirmNewPasswordButton = document.getElementById("toggle-confirm-new-password");

        function togglePasswordVisibility(inputElement, toggleButton) {
            if (inputElement.type === "password") {
                inputElement.type = "text";
                toggleButton.innerHTML = '<i class="icofont-eye-blocked"></i>';
            } else {
                inputElement.type = "password";
                toggleButton.innerHTML = '<i class="icofont-eye"></i>';
            }
        }

        toggleCurrentPasswordButton.addEventListener("click", function () {
            togglePasswordVisibility(currentPasswordInput, toggleCurrentPasswordButton);
        });

        toggleNewPasswordButton.addEventListener("click", function () {
            togglePasswordVisibility(newPasswordInput, toggleNewPasswordButton);
        });

        toggleConfirmNewPasswordButton.addEventListener("click", function () {
            togglePasswordVisibility(confirmNewPasswordInput, toggleConfirmNewPasswordButton);
        });
    });
</script>
