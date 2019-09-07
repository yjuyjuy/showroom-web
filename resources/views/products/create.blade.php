@extends('layouts.app')

@section('title', '添加新商品')

@section('content')
<div class="container">
	<div class="container__content">
		<form id="create-product-form" class="product-form text-left" action="{{route('products.store')}}" method="post">
			@csrf
			@include('products.edit.form')
			<div class="form-group">
				<div class="d-flex justify-content-end mt-3">
					<button class="mdc-button mdc-button--outlined">
					  <span class="mdc-button__label">{{ __('create') }}</span>
					</button>
				</div>
			</div>
		</form>
	</div>
</div>
@endsection
