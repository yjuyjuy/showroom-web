@extends('layouts.app')

@section('title', 'Following retailers')

@section('content')
<div class="d-flex flex-column mx-2">
	@foreach($retailers as $retailer)
	<div class="my-2">
		<a href="{{ route('retailer.products.index', ['retailer' => $retailer,]) }}" class="mdc-typography--headline6">{{ $retailer->name }}</a>
	</div>
	@endforeach
</div>
@endsection