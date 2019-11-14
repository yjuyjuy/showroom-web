@extends('layouts.app')

@section('title', $product->name_cn.' - Dior')

@section('content')
<div class="container">
	<div class="">
		@include('dior.show.images')
	</div>

	<div class="container__content d-flex flex-column">
		@include('dior.show.properties')
		@include('dior.show.customer')
	</div>
</div>
@include('layouts.back_fab')
@endsection
