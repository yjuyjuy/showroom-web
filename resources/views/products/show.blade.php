@extends('layouts.app')

@section('title',$product->brand->name.' '.$product->season->name.' '.$product->name_cn.' - TheShowroom')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-12 col-md-6 order-2 order-md-1">
			@include('products.show.images')
		</div>
		<div class="col-12 col-md-6 pb-4 d-flex justify-content-center order-1 order-md-2">
			<div class="d-flex flex-column" style="min-width:66%;">
				<div class="p-4 border my-2 my-md-4">
					@include('products.show.properties')
					@include('products.show.size_price')
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
