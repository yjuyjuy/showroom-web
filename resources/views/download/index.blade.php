@extends('layouts.app')

@section('title', '下载')

@section('content')
<div class="fullscreen-center">
	<div class="d-flex flex-column">
		<div class="text-center my-3">
			@foreach ($links as $name => $link)	
				<a href="{{ $link }}" class="mdc-typography--headline5">{{ $name }}</a>
			@endforeach
        </div>
	</div>
</div>
@endsection