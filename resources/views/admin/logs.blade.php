@extends('layouts.app')

@section('title', __('Logs').' - '.__('Admin'))

@section('content')
<div class="d-flex justify-content-center mt-4 mx-3">
	<div class="d-flex flex-column">
	@foreach($logs as $log)
		<div class="m-3">
			<a href="{{ $log->url }}">{{ $log->created_at }} {{ $log->content }}</a>
		</div>
	@endforeach
	@include('layouts.pages')
</div>
@endsection
