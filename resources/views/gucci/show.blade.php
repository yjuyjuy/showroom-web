@extends('layouts.app')

@section('title', $product->name.' - Gucci')

@section('content')
<div class="container">
	<div class="">
		@include('gucci.show.images')
	</div>

	<div class="container__content d-flex flex-column">
		@include('gucci.show.properties')
		@include('gucci.show.customer')
	</div>
</div>
@include('layouts.back_fab')
@endsection
