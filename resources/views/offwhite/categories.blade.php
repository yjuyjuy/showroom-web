@extends('layouts.app')

@section('title', 'Categories - Farfetch')

@section('content')
<div class="fullscreen-center">
	<div class="d-flex flex-column">
		<div class="my-3 d-flex justify-content-center flex-wrap">
			@foreach($categories->shuffle() as $token => $category)
				<a href="{{ route('offwhite.categories.index', ['category' => $category,]) }}" class="mdc-typography--headline6 my-2 mx-3">{{ $category }}</a>
			@endforeach
		</div>
	</div>
</div>
@endsection
