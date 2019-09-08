@extends('layouts.app')

@section('title', '修改商品 -'.$product->displayName())

@section('content')
<div class="container">
		<div class="">
			@include('products.show.images')
		</div>
		<div class="container__content">
			<form id="update-form" class="product-form text-left" action="{{ route('products.update',['product' => $product]) }}" method="post">
				@csrf
				@method('PATCH')
				@include('products.edit.form')
			</form>
			<div class="mt-3 d-flex justify-content-end">
				<button type="button" class="mdc-button mdc-button--outlined" onclick="window.history.back()">
				  <span class="mdc-button__label">{{ __('Back') }}</span>
				</button>
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
@endsection
