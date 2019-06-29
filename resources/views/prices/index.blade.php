@extends('layouts.app')

@section('title','价格表 - '.$vendor->name.' - TheShowroom')

@section('content')
<div class="container">
	@if(auth()->user()->isSuperAdmin())
	<div class="row mb-4">
		<form method="get" target="_self" class="col-auto mx-auto">
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
	<div class="row mb-md-4">
		<div class="col-md-6">
			<div class="row">
				<div class="col-6">
					<img class="w-100" src="/storage/images/{{ $product->images[0]->filename??'' }}">
				</div>
				<div class="col-6">
					<img class="w-100" src="/storage/images/{{ $product->images[1]->filename??'' }}">
				</div>
			</div>
		</div>
		<div class="col-md-6 d-flex justify-content-center align-items-center">
			<div class="" style="min-width:83.3%;">
				<div class="d-flex flex-column p-4">
					<div class="row pb-2 mb-2 border-bottom">
						<div class="col-12">
							<span>{{ $product->brand->full_name }}</span>
						</div>
						<div class="col-12">
							<span class="font-weight-bold">{{ $product->name_cn }}</span>
						</div>
					</div>
					<div class="row">
						<span class="col">尺码</span>
						<span class="col">成本</span>
						<span class="col">调货</span>
						<span class="col">零售</span>
					</div>
					@foreach($product->prices as $price)
					@foreach($price->data as $row)
					<div class="row">
						<span class="col">{{ $row['size'] }}</span>
						<span class="col">&yen;{{$row['cost']}}</span>
						<span class="col">&yen;{{$row['resell']}}</span>
						<span class="col">&yen;{{$row['retail']}}</span>
					</div>
					@endforeach
					<div class="row justify-content-end">
						<a href="{{route('prices.edit',['price'=>$price])}}" class="col-auto text-primary text-right pr-0">修改</a>
						<a href="#" class="col-auto col-md-2 text-danger" @click.prevent="deletePrice({{$price->id}})">删除</a>
					</div>
					@endforeach
				</div>
			</div>
		</div>
	</div>
	@endforeach
</div>
@endsection
