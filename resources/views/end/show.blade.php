@extends('layouts.app')

@section('title', $product->name.' - End Clothing')

@section('content')
<div class="container">
	<div class="">
		@include('end.show.images')
	</div>

	<div class="container__content d-flex flex-column">
		@include('end.show.properties')
		@include('end.show.customer')
	</div>
</div>
@include('layouts.back_fab')
@endsection
