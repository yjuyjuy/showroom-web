@extends('layouts.app')

@section('title','创建新商品 - TheShowroom')

@section('content')
<div class="">
	<div class="">

		<div class="">
			<form action="{{route('products.store')}}" method="post">
				@csrf
				@include('products.edit.form')
				<div class="form-group  ">
					<div class="">
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
