@extends('layouts.app')

@section('title','创建新商品 - TheShowroom')

@section('content')
<div id="products.create">
	<div class="row">

		<div class="col-md-6 mx-auto">
			<form id="create-product-form" class="product-form" action="{{route('products.store')}}" method="post">
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
</div>
@endsection
