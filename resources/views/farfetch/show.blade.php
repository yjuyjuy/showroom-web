@extends('layouts.app')

@section('title', $product->displayName())

@section('content')
<div class="container">
	<div class="">
		@include('farfetch.show.images')
	</div>

	<div class="container__content d-flex flex-column">
		@include('farfetch.show.properties')
		@include('farfetch.show.customer')
	</div>
</div>
@include('layouts.back_fab')
@endsection
