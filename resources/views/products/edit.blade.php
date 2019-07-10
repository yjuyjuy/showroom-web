@extends('layouts.app')

@section('title','修改商品-'.$product->displayName().'-TheShowroom')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-6">
			@include('products.show.images')
		</div>
		<div class="col-md-6">
			<form class="" action="/products/{{ $product->id }}" method="post" id="update-form">
				@csrf
				@method('PATCH')
				@include('products.edit.form')
			</form>
			<div class="form-group   ">
				<div class="">
					<a href="{{route('products.show',['product' => $product])}}" class="btn btn-outline-primary ">返回</a>
					<button type="submit" class="mdc-button mdc-button--outlined" form="update-form">
						更新
					</button>
					<form action="{{route('products.destroy',['product' => $product])}}" class="d-inline" method="post" id="delete-form">
						@csrf
						@method('DELETE')
						<button type="submit" class="mdc-button mdc-button--outlined" form="delete-form">
							删除
						</button>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
