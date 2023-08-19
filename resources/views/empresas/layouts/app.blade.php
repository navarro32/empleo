<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/empresas/empresas.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="{{ asset('css/empresas/empresas.css') }}" rel="stylesheet">
    <!-- <script src="{{ asset('/vendors/ckeditor/ckeditor.js') }}"></script> -->
    <style>
        #cke_editor1 {
            width: 100%
        }
    </style>
</head>

<body>
    <div id="app">
        <v-app id="inspire">
            <menutoolbar ref="componenteMenu" datos="{{ $datos }}" href="{{ route('logout') }}"></menutoolbar>
            <v-content>
                <v-container fluid fill-height>
                    <v-layout>
                        <transition name="slide-fade" mode="out-in">
                            <router-view :key="$route.fullPath"></router-view>
                        </transition>
                    </v-layout>
                </v-container>
            </v-content>
        </v-app>
    </div>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
</body>

</html>