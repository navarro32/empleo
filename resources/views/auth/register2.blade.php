@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header font-weight-bold text-center">Registro de Usuarios</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('register2') }}" enctype="multipart/form-data">
                        @csrf                        
                        <div class="form-group row mb-0">
                            <div class="input-group mb-2 col-md-6">
                                <div class="input-group-prepend ">
                                    <i class="material-icons input-group-text" style="display: flex;    align-items: center; font-size: 1.4rem;    padding: 0 10px;">perm_identity</i>
                                </div>
                                <input id="nombre" type="text" class="form-control @error('nombre') is-invalid @enderror" name="nombre" value="{{ old('nombre') }}" required  autocomplete="nombre" placeholder="Ingresa tu nombre">
                                @error('nombre')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="input-group mb-2 col-md-6">
                                <div class="input-group-prepend ">
                                    <i class="material-icons input-group-text" style="display: flex;    align-items: center; font-size: 1.4rem;    padding: 0 10px;">perm_identity</i>
                                </div>
                                <input id="apellido" type="text" class="form-control @error('apellido') is-invalid @enderror" name="apellido" value="{{ old('apellido') }}" required  autocomplete="apellido" placeholder="Ingresa tu Apellido">
                                @error('apellido')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="input-group mb-2 col-md-12">
                                <div class="input-group-prepend ">
                                    <i class="material-icons input-group-text" style="display: flex;    align-items: center; font-size: 1.4rem;    padding: 0 10px;">alternate_email</i>
                                </div>
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required  autocomplete="email" placeholder="Ingresa tu email">
                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="input-group mb-2 col-md-12">
                                <div class="input-group-prepend ">
                                    <i class="material-icons input-group-text" style="display: flex;    align-items: center; font-size: 1.4rem;    padding: 0 10px;">fingerprint</i>
                                </div>
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" placeholder="Ingresa tu contraseña" name="password" required autocomplete="new-password">
                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <input type="hidden" name="tipo" value="3">
                            <div class="input-group mb-2 col-md-12">
                                <div class="input-group-prepend ">
                                    <i class="material-icons input-group-text" style="display: flex;    align-items: center; font-size: 1.4rem;    padding: 0 10px;">fingerprint</i>
                                </div>
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" placeholder="Ingresa nuevamente tu contraseña" required autocomplete="new-password">
                                @error('password_confirmation')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4 offset-lg-5">
                                <button type="submit" class="btn btn-success col-12 col-md-auto">
                                   Registrar
                                </button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection