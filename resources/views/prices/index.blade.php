@extends('layouts.app')

@section('title','价格表 - '.$vendor->name.' - TheShowroom')

@section('content')
<div class="">
	@if(auth()->user()->isSuperAdmin())
	<div class="">
		<form method="get" target="_self" class="">
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
	<div class="">
		<div class="">
			<div class="">
				<div class="">
					<img class="" src="/storage/images/{{ $product->images[0]->filename??'' }}">
				</div>
				<div class="">
					<img class="" src="/storage/images/{{ $product->images[1]->filename??'' }}">
				</div>
			</div>
		</div>
		<div class="">
			<div class="  align-self-center" style="min-width:83.3%;">
				<div class=" border  ">
					<div class="">
						<div class="">
							<span>{{ $product->brand->full_name }}</span>
						</div>
						<div class="">
							<span>{{ $product->name_cn }}</span>
						</div>
					</div>
					<div class="">
						<span class="">尺码</span>
						<span class="">成本</span>
						<span class="">调货</span>
						<span class="">零售</span>
					</div>
					@foreach($product->prices as $price)
					@foreach($price->data as $row)
					<div class="">
						<span class="">{{ $row['size'] }}</span>
						<span class="">&yen;{{$row['cost']}}</span>
						<span class="">&yen;{{$row['resell']}}</span>
						<span class="">&yen;{{$row['retail']}}</span>
					</div>
					@endforeach
					<div class="">
						<a href="{{route('prices.edit',['price'=>$price])}}" class=" text-primary">修改</a>
						<a href="#" class=" text-danger" @click.prevent="deletePrice({{$price->id}})">删除</a>
					</div>
					@endforeach
				</div>
			</div>
		</div>
	</div>
	@endforeach
</div>
@endsection
