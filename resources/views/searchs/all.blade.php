@extends('layouts.app')

@section('content')
 <v-app id="inspire">
<div class="container">
    <buscador @if(isset($_GET['campo'])) props-campo="{{$_GET['campo']}}" @endif></buscador>
</div>
</v-app>
@endsection
