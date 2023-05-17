<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tela de cadastro</title>
    <link rel="stylesheet" href=" {{ asset('css/bootstrap.min.css') }}">
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-info bg-info">
        <div class="container">
            <a class="navbar-brand" href="#">Orbita-sincronizador Painel</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#">Cadastrar loja</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Lista de loja</a>
                    </li>

                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-4 col-md-6 col-sm-8">
                <div class="card mt-5">
                    <div class="card-body">
                        <h5 class="card-title text-center">Cadastrar Loja</h5>

                        <span style="color: red">{{ Session::get('msg-error-cadastro') }}</span>
                        <span style="color:green">{{ Session::get('msg-success-cadastro') }}</span>

                        <form action="{{ route('cadastro-loja') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="" class="form-label">Cnpj master</label>
                                <input type="text" class="form-control" id="" name="cnpj_cliente">
                                @error('cnpj_cliente')
                                <span class="danger text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="" class="form-label">Cnpj loja</label>
                                <input type="text" class="form-control" id="" name="cnpj_loja">
                                <small id="emailHelp" class="form-text text-muted">Caso não tenha um cnpj a aplicação vai criar um id aleatorio no lugar</small>
                            </div>
                            <div class="mb-3">
                                <label for="" class="form-label">Nome loja</label>
                                <input type="text" class="form-control" id="" name="nome_loja">
                                @error('nome_loja')
                                <span class="danger text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-info">Cadastar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- <script src="{{ asset('js/bootstrap.bundle.js') }}"></script> --}}
    <script src="{{ asset('js/bootstrap.js') }}"></script>
</body>

</html>
