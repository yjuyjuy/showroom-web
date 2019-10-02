@extends('layouts.app')

@section('title', 'Designers - Farfetch')

@section('content')
<div class="fullscreen-center">
	<div class="d-flex flex-column align-items-center mdc-typography--headline5">
		@foreach($designers as $designer)
		<div class="my-4">
			<a class="px-4 py-2" href="{{ route('farfetch.designers.index', ['designer' => $designer]) }}" style="text-transform: uppercase;">{{ $designer->description }}</a>
		</div>
		@endforeach
	</div>
</div>
@endsection
