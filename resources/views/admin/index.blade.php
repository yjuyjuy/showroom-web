@extends('layouts.app')

@section('title', __('Advanced Functions').' - '.__('Admin')))

@section('content')
<div class="fullscreen-center">
	<div class="d-flex flex-column text-center">
		@foreach($functions as $name => $description)
		<div class="my-4 mdc-typography--headline5"><a href="{{ route('admin.call', ['function' => $name,]) }}">{{ $description }}</a></div>
		@endforeach
	</div>
</div>
@endsection
