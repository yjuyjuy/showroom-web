@extends('layouts.app')

@section('title', $product->name.' - Balenciaga')

@section('content')
<div class="container">
	<div class="">
		@include('balenciaga.show.images')
	</div>

	<div class="container__content d-flex flex-column">
		@include('balenciaga.show.properties')
		@include('balenciaga.show.customer')
	</div>
</div>
@include('layouts.back_fab')
@endsection
