@extends('layouts.admin')
@section('content')

<div id="app">
	
<!-- <product-component></product-component> -->
<users2-component></users2-component>

@foreach($users as $user)
	<h3>{{ $user->surname }}</h3>
@endforeach

</div>
<script src="{{ asset('js/app.js') }}"></script>

@endsection