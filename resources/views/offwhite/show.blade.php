@extends('layouts.app')

@section('title', $product->name.' - OFF-WHITE')

@section('content')
<div class="container">
	<div class="">
		@include('offwhite.show.images')
	</div>

	<div class="container__content d-flex flex-column">
		@include('offwhite.show.properties')
		@include('offwhite.show.customer')
	</div>
</div>
@include('layouts.back_fab')
@endsection
