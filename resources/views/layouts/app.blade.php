<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>
        #cke_editor1 {
            width: 100%
        }

    </style>
</head>
<body>

    <div id="app" data-app>
        <v-navigation-drawer class="d-block d-lg-none" v-model="drawer" fixed left temporary>
            <v-list dense>
                <v-list-item link>
                    <v-list-item-action>
                        <v-icon>mdi-home</v-icon>
                    </v-list-item-action>
                    <v-list-item-content>
                        <v-list-item-title>Home</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
                <v-list-item link>
                    <v-list-item-action>
                        <v-icon>mdi-email</v-icon>
                    </v-list-item-action>
                    <v-list-item-content>
                        <v-list-item-title>Contact</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
            </v-list>
        </v-navigation-drawer>
        <v-app-bar height="90" fixed style="position: sticky;" color="gray" elevate-on-scroll>
            @guest
            <v-app-bar-nav-icon @click.stop="drawer = !drawer" class="d-lg-none"></v-app-bar-nav-icon>
            @endguest
            <v-toolbar-title>
                <a href="{{ url('/') }}"><img class="logo" src="{{ asset('images/empleo.png') }}" alt="Logo  empleo"></a>
            </v-toolbar-title>
            <v-spacer></v-spacer>
            <v-toolbar-items class="hidden-sm-and-down">
                @guest
                <v-btn text href="{{ route('login') }}">
                    <span class="mr-2">Empresas</span>
                </v-btn>
                <v-btn text href="{{ route('login2') }}">
                    <span class="mr-2">Personas</span>
                </v-btn>
                @else
                <menu-user :user="{{ Auth::user() }}" :basicos="@if(Auth::user()->tipo_user==2) {{ Auth::user()->empresas }} @elseif(Auth::user()->tipo_user==3) {{ Auth::user()->datosBasicos }} @endif"></menu-user>
                @endguest
            </v-toolbar-items>
        </v-app-bar>
        <form id="logout-form" ref="cerrar_session" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
        <main style="min-height: 70vh;">
            @yield('content')
        </main>
        <footer class="footer text-center p-5">
            &copy; Todos los derechos reservados 
        </footer>
    </div>

    <script src="{{  asset('sweetalert/sweetalert.min.js')  }}"></script>
    @include('sweet::alert')
</body>
</html>
