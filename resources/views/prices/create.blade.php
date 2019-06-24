@extends('layouts.app')

@section('title','添加报价-'.$product->displayName().'-TheShowroom')

@section('content')
<div class="">
	<div class=" ">
		<div class="  ">
			@include('products.show.images')
		</div>
		<div class=" s  ">
			<form action="{{route('prices.store',['product'=>$product])}}" method="post" class=" " id="create-form">
				@csrf
				@if(auth()->user()->isSuperAdmin())
				<input type="hidden" name="vendor" value="{{$vendor->id}}">
				<div class="  h3">{{$vendor->name.' - '.$vendor->city}}</div>
				@endif
				<prices-editor v-bind:input="[]"></prices-editor>
			</form>
			<div class="  ">
				<div class="">
					<a href="{{route('products.show',['product' => $product])}}" class="btn btn-primary ">Back</a>
					<button type="submit" class="btn btn-primary" form="create-form">Submit</button>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
