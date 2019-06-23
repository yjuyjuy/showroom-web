@extends('layouts.app')

@section('title','价格表 - '.$vendor->name.' - TheShowroom')

@section('content')
<div class="container">
	@if(auth()->user()->isSuperAdmin())
	<div class="row justify-content-center mb-2 mb-md-4">
		<form method="get" target="_self" class="col-auto">
			<select onchange="submit()" class="form-control" name="vendor">
				<option value=""></option>
				@foreach(\App\Vendor::all() as $v)
				<option value="{{$v->id}}" {{(old('vendor') == $v->id)?' selected':''}}>{{$v->name}}</option>
				@endforeach
			</select>
		</form>
	</div>
	@endif
	@foreach($vendor->products as $product)
	<div class="row justify-content-center">
		<div class="col-md-6">
			<div class="row">
				<div class="col-6 pb-3">
					<img class="w-100" src="/storage/images/{{ $product->images[0]->filename??'' }}">
				</div>
				<div class="col-6 pb-3">
					<img class="w-100" src="/storage/images/{{ $product->images[1]->filename??'' }}">
				</div>
			</div>
		</div>
		<div class="col-md-6 pb-4 d-flex justify-content-center">
			<div class="d-flex flex-column align-self-center" style="min-width:83.3%;">
				<div class="p-4 border my-2 my-md-4">
					<div class="row text-center">
						<div class="col-12">
							<span>{{ $product->brand->full_name }}</span>
						</div>
						<div class="col-12">
							<span>{{ $product->name_cn }}</span>
						</div>
					</div>
					<div class="row text-center no-gutters">
						<span class="col-3">尺码</span>
						<span class="col-3">成本</span>
						<span class="col-3">调货</span>
						<span class="col-3">零售</span>
					</div>
					@foreach($product->prices as $price)
					@foreach($price->data as $row)
					<div class="row text-center no-gutters">
						<span class="col-3">{{ $row['size'] }}</span>
						<span class="col-3">&yen;{{$row['cost']}}</span>
						<span class="col-3">&yen;{{$row['resell']}}</span>
						<span class="col-3">&yen;{{$row['retail']}}</span>
					</div>
					@endforeach
					<div class="row text-center no-gutters justify-content-end">
						<a href="{{route('prices.edit',['price'=>$price])}}" class="col-3 text-primary">修改</a>
						<a href="#" class="col-3 text-danger" @click.prevent="deletePrice({{$price->id}})">删除</a>
					</div>
					@endforeach
				</div>
			</div>
		</div>
	</div>
	@endforeach
</div>
@endsection
