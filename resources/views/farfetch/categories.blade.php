@extends('layouts.app')

@section('title', 'Categories - Farfetch')

@section('content')
<div class="fullscreen-center">
	<div class="d-flex flex-column">
		<div class="my-3 d-flex justify-content-center flex-wrap">
			@foreach($categories->filter(function($item){ return strpos($item->url_token, '-1'); })->shuffle() as $category)
				<a href="{{ route('farfetch.categories.index', ['category' => $category,]) }}" class="mdc-typography--headline6 my-2 mx-3">{{ $category->description }}</a>
			@endforeach
		</div>
			<div class="my-3 d-flex justify-content-center flex-wrap">
			@foreach($categories->filter(function($item){ return strpos($item->url_token, '-2'); })->shuffle() as $category)
				<a href="{{ route('farfetch.categories.index', ['category' => $category,]) }}" class="mdc-typography--headline6 my-2 mx-3">{{ $category->description }}</a>
			@endforeach
		</div>
	</div>
</div>
@endsection
