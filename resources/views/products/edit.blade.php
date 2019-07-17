@extends('layouts.app')

@section('title',__('Edit product').'-'.$product->displayName().'-TheShowroom')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-6">
			@include('products.show.images')
		</div>
		<div class="col-md-6">
			<form id="update-form" class="product-form" action="{{ route('products.update',['product' => $product]) }}" method="post">
				@csrf
				@method('PATCH')
				@include('products.edit.form')
			</form>
			<div class="mt-3 d-flex justify-content-end">
				<a href="{{route('products.show',['product' => $product])}}" class="mdc-button mdc-button--outlined">
				  <span class="mdc-button__label">{{ __('back') }}</span>
				</a>
				<button type="submit" class="mdc-button mdc-button--outlined ml-2" form="update-form">
					<span class="mdc-button__label">{{ __('update') }}</span>
				</button>
				<button type="submit" class="mdc-button mdc-button--outlined mdc-button--error ml-2" form="delete-product-form">
					<span class="mdc-button__label">{{ __('delete') }}</span>
				</button>
				<form action="{{route('products.destroy',['product' => $product])}}" class="d-none" method="post" id="delete-product-form">
					@csrf
					@method('DELETE')
				</form>
			</div>
		</div>
	</div>
</div>
@endsection
