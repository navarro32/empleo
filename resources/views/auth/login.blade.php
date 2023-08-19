@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 col-md-6">
            <div class="card">
                <div class="card-header text-center font-weight-bold">Login Empresas</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('recibelogin') }}">
                        @csrf

                        <div class="form-group row">
                            <div class="input-group col-md-12">
                                <div class="input-group-prepend ">
                                    <i class="material-icons input-group-text" style="display: flex;    align-items: center; font-size: 1.4rem;    padding: 0 10px;">alternate_email</i>
                                </div>
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autofocus autocomplete="email" placeholder="Ingresa tu email">
                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="input-group col-md-12">
                                <div class="input-group-prepend">
                                    <i class="material-icons input-group-text" style="display: flex;    align-items: center; font-size: 1.4rem;    padding: 0 10px;">fingerprint</i>
                                </div>
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Ingresa tu contraseña">
                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6">

                                <div class="custom-control custom-checkbox" style="cursor:pointer">
                                    <input class="custom-control-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="remember" style="cursor:pointer"> Recordar ingreso</label>
                                </div>
                            </div>
                        </div>


                        <div class="form-group row offset-1 ">
                            <button type="submit" class="btn btn-success col-5 mr-1">
                                Ingresar
                            </button>
                            <a name="" id="" class="btn btn-primary col-5" href="{{ route('register')}}" role="button">Registrar Empresa</a>

                            @if (Route::has('password.request'))
                            <a class="btn btn-link col-12" href="{{ route('password.request') }}">
                                Olvido su contraseña?
                            </a>
                            @endif
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection