@extends('layouts.app')

@section('content')
<script src="{{ asset('/vendors/ckeditor/ckeditor.js') }}" type="application/javascript"></script>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Registro de Empresas</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group m-0">
                            <p class="m-0 font-weight-bold">Datos del representante</p>
                        </div>
                        <hr class="mt-0">
                        <div class="form-group row mb-1">
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
                        <div class="form-group mt-2 mb-1">
                            <p class="m-0 font-weight-bold">Datos de la empresa</p>
                        </div>
                        <hr class="mt-0">

                        <div class="form-group row mb-1">
                            <div class="input-group mb-2 col-md-12">
                                <div class="input-group-prepend ">
                                    <i class="material-icons input-group-text" style="display: flex;    align-items: center; font-size: 1.4rem;    padding: 0 10px;">business</i>
                                </div>
                                <input id="razon_social" type="text" class="form-control @error('razon_social') is-invalid @enderror" name="razon_social" value="{{ old('razon_social') }}" required  autocomplete="razon_social" placeholder="Ingresa la razón social">
                                @error('razon_social')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="input-group mb-2 col-12 col-md-6">
                                <div class="input-group-prepend ">
                                    <i class="material-icons input-group-text" style="display: flex;    align-items: center; font-size: 1.4rem;    padding: 0 10px;">work_outline</i>
                                </div>
                                <input id="nit" type="text" class="form-control @error('nit') is-invalid @enderror" name="nit" value="{{ old('nit') }}" required  autocomplete="nit" placeholder="Ingresa el NIT de la empresa">
                                @error('nit')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="input-group mb-2 col-12 col-md-12">
                                <div class="input-group-prepend ">
                                    <i class="material-icons input-group-text" style="display: flex;    align-items: center; font-size: 1.4rem;    padding: 0 10px;">room</i>
                                </div>
                                <input id="direccion" type="text" class="form-control @error('direccion') is-invalid @enderror" name="direccion" value="{{ old('direccion') }}" required  autocomplete="direccion" placeholder="Ingresa la dirección de la empresa">
                                @error('direccion')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="input-group mb-2 col-12 col-md-5">
                                <div class="input-group-prepend ">
                                    <i class="material-icons input-group-text" style="display: flex;    align-items: center; font-size: 1.4rem;    padding: 0 10px;">phone</i>
                                </div>
                                <input id="telefono" type="text" class="form-control @error('telefono') is-invalid @enderror" name="telefono" value="{{ old('telefono') }}" required autocomplete="telefono" placeholder="Teléfono de la empresa">
                                @error('telefono')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <input type="hidden" name="tipo" value="2">
                            <div class="input-group mb-2 col-12 col-md-7">
                                <div class="input-group-prepend ">
                                    <i class="material-icons input-group-text" style="display: flex;    align-items: center; font-size: 1.4rem;    padding: 0 10px;">http</i>
                                </div>
                                <input id="url" type="url" class="form-control @error('url') is-invalid @enderror" name="url" value="{{ old('url') }}"  autofocus autocomplete="url" placeholder="Url de la web de la empresa">
                                @error('url')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <avatar @error('logo') errorvalidar="{{ $message }}" @enderror></avatar>

                        </div>

                        <div class="form-group mt-2 mb-1">
                            <p class="m-0 font-weight-bold">Descripción de la empresa</p>
                        </div>
                        <hr class="mt-0">
                        <div class="form-group row mb-1">
                            <div class="input-group mb-2 col-12 col-md-12">
                                <textarea placeholder="Puedes insertar datos de tu empresa" class="ckeditor w-100" name="descripcion" id="editor1" rows="10" cols="80">
                                {{ old('descripcion') }}
                            </textarea>
                            </div>
                        </div>


                        <div class="form-group row mb-1">
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