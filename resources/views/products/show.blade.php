@extends('layouts.app')

@section('title',$product->brand->name.' '.$product->season->name.' '.$product->name_cn.' - TheShowroom')

@section('content')
<div class="container">
	<div class="row justify-content-center">
		<div class="col-12 col-md-6 order-2 order-md-1">
			@include('products.show.images')
		</div>
		<div class="col-12 col-md-5 pb-4 d-flex justify-content-center order-1 order-md-2">
			<div class="d-flex flex-column" style="min-width:83.3%;">
				<div class="p-4 border my-2 my-md-4">
					@include('products.show.properties')
					@include('products.show.size_price')
				</div>

				@if(($user = auth()->user()) && ($vendor = $user->vendor))
						@if($product->prices->isNotEmpty())
						<div class="p-4 border my-2 my-md-4">
							@include('products.show.size_resell')
						</div>
						@endif

						@if($user->isSuperAdmin())
						<div class="p-4 border my-2 my-md-4">
							@include('products.show.admin')
						</div>

						@else
							@if($product->prices->firstWhere('vendor_id',$vendor->id))
							<div class="p-4 border my-2 my-md-4">
								@include('products.show.vendor')
							</div>
							@else
							<a href="{{route('prices.create',['product'=>$product])}}" class="btn btn-primary">添加报价</a>
							@endif
						@endif
				@endif
				<div class="my-2 my-md-4 d-flex justify-content-end">
					<a href="{{route('products.index')}}" class="btn btn-primary">Back</a>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
