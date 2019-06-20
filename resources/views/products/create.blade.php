@extends('layouts.app')

@section('title','创建新商品 - TheShowroom')

@section('content')
<div class="container">
	<div class="row justify-content-center">

		<div class="col-md-6">
			<form action="{{route('products.store')}}" method="post">
				@csrf
				@include('products.edit.form')
				<div class="form-group row mb-0 justify-content-end">
					<div class="col-auto">
						<button type="submit" class="btn btn-primary">
							创建
						</button>
					</div>
				</div>
			</form>
		</div>

	</div>
</div>
@endsection
