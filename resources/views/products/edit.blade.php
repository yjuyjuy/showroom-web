@extends('layouts.app')

@section('title','创建新商品 - TheShowroom')

@section('content')
<div class="container-fluid">
	<div class="row">

		<div class="col-8 offset-2">
			<form action="/products/{{ $product->id }}" method="post">
				@method('PATCH')
				@include('products.form')
				<div class="form-group row mb-0">
					<div class="col-md-6 offset-md-4">
						<button type="submit" class="btn btn-primary">
								更新
						</button>
					</div>
				</div>

			</form>
		</div>

	</div>
</div>
@endsection
