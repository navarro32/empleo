@extends('layouts.app')

@section('content')
<v-container fluid>
    <v-row>
    @if($aplico)
    <v-col cols="12">
    <v-alert
      outlined
      type="success"
      text
      color="success"
    >
      Ya has aplicado a esta oferta
    </v-alert>
    </v-col>
    @endif
        <v-col cols="12 col-md-8" align-self="stretch">
            <v-card>
                <v-card-title>
                    <h1 class="title-ofert">{{ $oferta->titulo }}</h1>
                </v-card-title>
                <v-card-text>
                    <div>
                        {!! $oferta->descripcion !!}
                    </div>
                    <div class="mt-4">
                        <h4 class="h4-ofert">Descripción de la empresa</h4>
                        {!! $oferta->user->empresas->descripcion !!}
                    </div>
                </v-card-text>
            </v-card>
        </v-col>
        <v-col cols="12 col-md-4" align-self="stretch">
            <v-card>
                <v-card-title>
                    <v-avatar class="py-2" tile color="gray" size="100%" style="border:solid 1px #e8e8e8">
                        <v-img max-height="150" contain class="col-12 col-md-6 col-lg-4" src="{{ $oferta->user->empresas->logo }}"></v-img>
                    </v-avatar>
                </v-card-title>
                <v-card-subtitle class="pt-2">
                    <h4 class="h4-ofert m-0">{{ $oferta->user->empresas->razon_social }}</h4>
                </v-card-subtitle>
                <v-card-text>
                    @if(!$aplico)
                    <v-btn block color="#314ba3" dark @click="$store.dispatch('aplicar', '{{$oferta->uuid}}')">Aplicar</v-btn>
                    @endif
                    <div class="mt-3">
                        <p class="mb-1">
                            <v-icon style="color:black; font-size:1rem; margin-top:-3px">mdi-map-marker</v-icon>{{ $oferta->ciudad->ciudad }}, {{ $oferta->ciudad->departamento->departamento }}
                        </p>
                        <p class="mb-1">
                            <v-icon style="color:black; font-size:1rem; margin-top:-3px">mdi-file-document-outline</v-icon>{{ $oferta->contrato_letra }}
                        </p>
                        <p class="mb-1">
                            <v-icon style="color:black; font-size:1rem; margin-top:-3px">mdi-account-tie</v-icon>{{ $oferta->experiencia }} Meses de experiencia
                        </p>
                    </div>
                </v-card-text>
            </v-card>
        </v-col>
</v-container>
@endsection
