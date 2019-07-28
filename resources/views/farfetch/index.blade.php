@extends('layouts.app')

@section('title','Farfetch '.__('database').' - TheShowroom')

@section('content')
<div id="products-index" class="">
	<ul class="mdc-image-list main-image-list">
		@foreach($products as $product)
		<li class="mdc-image-list__item">
			<a href="{{ route('farfetch.show',['product' => $product ]) }}">
				<div class="">
					<img class="mdc-image-list__image" src="{{$product->images->first()->url ?? asset('storage/icons/ImagePlaceholder.svg')}}">
				</div>
				<div class="mdc-image-list__supporting">
					<span class="mdc-image-list__label brand">{{ $product->designer->name }}</span>
					<span class="mdc-image-list__label">{{ $product->shortDescription }}</span>
				</div>
			</a>
		</li>
		@endforeach
	</ul>
</div>
@endsection
