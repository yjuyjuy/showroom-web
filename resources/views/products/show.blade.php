@extends('layouts.app')

@section('title',$product->brand->name.' '.$product->season->name.' '.$product->name_cn.' - TheShowroom')

@section('content')
<div class="">
	<div class=" ">
		<div class="  ">
			@include('products.show.images')
		</div>
		<div class="     ">
			<div class=" " style="min-width:83.3%;">
				<div class=" border  ">
					@include('products.show.properties')
					@include('products.show.size_price')
				</div>

				@if(($user = auth()->user()) && ($vendor = $user->vendor))
				@if($product->prices->isNotEmpty())
				<div class=" border  ">
					@include('products.show.size_resell')
				</div>
				@endif

				@if($user->isSuperAdmin())
				<div class=" border  ">
					@include('products.show.admin')
				</div>

				@else
				@if($product->prices->firstWhere('vendor_id',$vendor->id))
				<div class=" border  ">
					@include('products.show.vendor')
				</div>
				@else
				<a href="{{route('prices.create',['product'=>$product])}}" class="btn btn-primary">添加报价</a>
				@endif
				@endif
				@endif
				<div class="   ">
					<a href="{{route('products.index')}}" class="btn btn-primary">Back</a>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
