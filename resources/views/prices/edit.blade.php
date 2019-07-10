@extends('layouts.app')

@section('title','修改报价-'.$product->displayName().'-TheShowroom')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-6">
			@include('products.show.images')
		</div>
		<div class="col-md-6">
			<form action="{{route('prices.update',['price' => $price])}}" method="post" class="" id="update-form">
				@csrf
				@method('PATCH')
				@if(auth()->user()->isSuperAdmin())
				<div class="row mx-2 my-4">
					<div class="col font-weight-bold">
						{{$price->vendor->name.' - '.$price->vendor->city}}
					</div>
				</div>
				@endif
				<prices-editor v-bind:input='@json(array_values($price->data))'></prices-editor>
			</form>
			<div class="container-fluid">
				<div class="row no-gutters">
					<a href="{{route('products.show',['product' => $product])}}" class="btn btn-outline-secondary">返回</a>
					<button type="submit" class="mdc-button mdc-button--outlined ml-2" form="update-form">提交</button>
					<button type="button" class="mdc-button mdc-button--outlined ml-2" @click="deletePrice({{$price->id}})">全部删除</button>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
