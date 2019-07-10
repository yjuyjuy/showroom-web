@extends('layouts.app')

@section('title','创建新商品 - TheShowroom')

@section('content')
<div class="container">
	<div class="row">

		<div class="col-md-6 mx-auto">
			<form action="{{route('products.store')}}" method="post">
				@csrf
				@include('products.edit.form')
				<div class="form-group  ">
					<div class="">
						<button type="submit" class="mdc-button mdc-button--outlined">
							创建
						</button>
					</div>
				</div>
			</form>
		</div>

	</div>
</div>
@endsection
