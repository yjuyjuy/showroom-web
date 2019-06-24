@extends('layouts.app')

@section('title',$product->brand->name.' '.$product->season->name.' '.$product->name_cn.' - TheShowroom')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-6">
			@include('products.show.images')
		</div>
		<div class="col-md-auto">
			<div class="" style="min-width:83.3%;">

				<div class="my-4 pl-4">
					@include('products.show.properties')
				</div>

				<div class="my-4 pl-4">
					@include('products.show.size_price')
				</div>

				@if(($user = auth()->user()) && ($vendor = $user->vendor))
				@if($product->prices->isNotEmpty())
				<div class="my-4 pl-4">
					@include('products.show.size_resell')
				</div>
				@endif

				@if($user->isSuperAdmin())
				<div class="my-4 pl-4">
					@include('products.show.admin')
				</div>

				@else
				@if($product->prices->firstWhere('vendor_id',$vendor->id))
				<div class="my-4 pl-4 ">
					@include('products.show.vendor')
				</div>
				@else
				<a href="{{route('prices.create',['product'=>$product])}}" class="btn btn-primary">添加报价</a>
				@endif
				@endif
				@endif
				<div class="my-4 pl-4">
					<a href="{{route('products.index')}}" class="">Back</a>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
