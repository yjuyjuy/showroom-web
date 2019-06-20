@extends('layouts.app')

@section('title','修改商品 '.$product->displayName().' - TheShowroom')

@section('content')
<div class="container">
	<div class="row justify-content-center">
		<div class="col-md-6 order-2 order-md-1">
			<div class="row">
				@foreach($product->images as $image)
				<div class="col-6 mb-3">
					<img class="w-100" src="/storage/images/{{$image->filename}}" alt="">
				</div>
				@endforeach
			</div>
		</div>
		<div class="col-md-5 order-1 order-md-2 mb-3">
			<form class="my-md-4" action="/products/{{ $product->id }}" method="post" id="update-form">
				@csrf
				@method('PATCH')
				@include('products.edit.form')
			</form>
			<div class="form-group row mb-0 justify-content-end">
				<div class="col-auto">
					<a href="{{route('products.show',['product' => $product])}}" class="btn btn-primary mr-2">返回</a>
					<button type="submit" class="btn btn-primary mr-2" form="update-form">
						更新
					</button>
					<form action="{{route('products.destroy',['product' => $product])}}" class="d-inline" method="post" id="delete-form">
						@csrf
						@method('DELETE')
						<button type="submit" class="btn btn-danger" form="delete-form">
							删除
						</button>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
