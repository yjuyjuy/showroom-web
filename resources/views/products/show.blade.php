@extends('layouts.app')

@section('title',$product->brand->name.' '.$product->season->name.' '.$product->name_cn.' - TheShowroom')

@section('content')
<div class="container-fluid">
	<div class="row">

		<div class="col-2 justify-content-center">
			@section('left-aside')
			<div class="d-flex flex-column text-center justify-content-center vh-100">

				<div class="align-self-center mt-n5">
					<a href="/products?=brand[]={{$product->brand->id}}">{{ $product->brand->full_name }}</a>
				</div>

				<div class="align-self-center">
					<span><a href="/products?season[]={{$product->season->id}}">{{ $product->season->name }}</a> {{ $product->name_cn }}</span>
				</div>

				<div class="align-self-center">
					<span>{{ $product->id }}</span>
				</div>

				<div class="align-self-center">
					<a href="/products/{{ $product->id }}/edit">edit</a>
				</div>

			</div>
			@endsection
			@yield('left-aside')
		</div>

		<div class="col-8">
			@section('center')
				<div class="row">
					@foreach($product->images as $image)
						<div class="col-6 pb-4">
							<img class="w-100" src="/storage/images/{{ $image->filename }}">
						</div>
					@endforeach
				</div>
			@endsection
			@yield('center')
		</div>

		<div class="col-2 justify-content-center">
			<div class="row text-center justify-content-center min-vh-100">
				@section('right-aside')
				<div class="col-12 col-sm-10 align-self-center mt-n5">
					<div class="border py-4">
					@if(!empty($sizes))
					@foreach($sizes as $size => $price)
						<div class="">{{ $size }} - &yen;{{$price}}</div>
					@endforeach
					@else
					<div class="">Currently not available</div>
					@endif
					</div>
				</div>
				@endsection
				@yield('right-aside')
			</div>
		</div>

	</div>
</div>
@endsection
