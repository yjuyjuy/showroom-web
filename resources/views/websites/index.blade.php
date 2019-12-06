@extends('layouts.app')

@section('title', '网站数据库')

@section('content')
<div class="fullscreen-center">
	<div class="d-flex flex-column">
		@foreach($websites as $token => $website)
		<div class="text-center my-3">
			<a href="{{ route($token.'.index') }}" class="mdc-typography--headline5">{{ $website }}</a>
		</div>
		@endforeach
	</div>
</div>
@endsection
