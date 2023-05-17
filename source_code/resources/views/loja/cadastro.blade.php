@extends('tamplate.main')
@section('titulo','Tela de cadastro')
@section('conteudo')
    <div class="row justify-content-center">
        <div class="col-lg-4 col-md-6 col-sm-8">
            <div class="card mt-5">
                <div class="card-body">
                    <h5 class="card-title text-center">Cadastrar Loja</h5>

                    <span style="color: red">{{ Session::get('msg-error-cadastro') }}</span>
                    <span style="color:green">{{ Session::get('msg-success-cadastro') }}</span>

                    <form action="{{ route('cadastro-loja') }}" method="GET">

                        <div class="mb-3">
                            <label for="" class="form-label">Cnpj master</label>
                            <input type="text" class="form-control" value="{{ old('cnpj_cliente') }}" id="" name="cnpj_cliente">
                            @error('cnpj_cliente')
                                <span class="danger text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="" class="form-label">Cnpj loja</label>
                            <input type="text" class="form-control" {{ old('cnpj_loja') }} id="" name="cnpj_loja">
                            <small id="emailHelp" class="form-text text-muted">Caso não tenha um cnpj a aplicação vai criar
                                um id aleatorio no lugar</small>
                        </div>
                        <div class="mb-3">
                            <label for="" class="form-label">Nome loja</label>
                            <input type="text" class="form-control" id="" value="{{ old('nome_loja') }}" name="nome_loja">
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
@endsection
