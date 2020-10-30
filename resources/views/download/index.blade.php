@extends('layouts.app')

@section('title', '下载')

@section('content')
<div class="fullscreen-center">
	<div class="d-flex flex-column">
		@foreach ($links as $name => $link)	
			<div class="text-center my-3">
				<a href="{{ $link }}" class="mdc-typography--headline5">{{ $name }}</a>
			</div>
		@endforeach
	</div>
</div>
@endsection