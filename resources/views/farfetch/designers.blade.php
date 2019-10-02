@extends('layouts.app')

@section('title', 'Designers - Farfetch')

@section('content')
<div class="fullscreen-center">
	<div class="d-flex flex-column align-items-center mdc-typography--headline5">
		<div class="my-4">
			<span class="px-4 py-2">设计师品牌</span>
		</div>
		@foreach($designers as $designer)
		<div class="my-4">
			<a class="px-4 py-2" href="{{ route('farfetch.designers.index', ['designer' => $designer]) }}">{{ $designer->description }}</a>
		</div>
		@endforeach
	</div>
</div>
@endsection
