@extends('layouts.app')

@section('title','修改报价-'.$product->displayName().'-TheShowroom')

@section('content')
<div class="images-content-container">
	<div class="images-container">
		@include('products.show.images')
	</div>
	<div class="content-container">
		@if(auth()->user()->isSuperAdmin())
		<div class="w-100">
			<div class="font-weight-bold text-left">
				{{$price->vendor->name.' - '.$price->vendor->city}}
			</div>
		</div>
		@endif

		<form action="{{route('prices.update',['price' => $price])}}" method="post" id="update-form">
			@csrf
			@method('PATCH')
			<prices-editor v-bind:input='@json(array_values($price->data))'></prices-editor>
		</form>

		<div class="d-flex justify-content-end">
			<a href="{{ route('products.show',['product' => $product]) }}" class="mdc-button mdc-button--outlined">
				<span class="mdc-button__label">返回</span>
			</a>
			<button type="submit" class="mdc-button mdc-button--outlined ml-2" form="update-form">
				<span class="mdc-button__label">提交</span>
			</button>
			<button type="button" class="mdc-button mdc-button--outlined mdc-button--error ml-2" @click="deletePrice({{$price->id}})">
				<span class="mdc-button__label">全部删除</span>
			</button>
		</div>
	</div>
</div>
@endsection
