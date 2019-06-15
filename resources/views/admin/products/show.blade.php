@extends('products.show')

@section('right-aside')
<div class="py-4 my-2 mx-auto col-12 col-md-10 border">
	<div class="row">
		<span class="col text-center">尺码</span>
		<span class="col text-center">成本</span>
		<span class="col text-center">调货</span>
		<span class="col text-center">零售</span>
	</div>
	@foreach($product->getSizeAllPrice() as $size => $values)
	<div class="row">
		<span class="col text-center">{{$size}}</span>
		<span class="col text-center">{{$values['cost']}}</span>
		<span class="col text-center">{{$values['resell']}}</span>
		<span class="col text-center">{{$values['retail']}}</span>
	</div>
	@endforeach
</div>

<div class="py-4 my-2 mx-auto col-12 col-md-10 border">
@foreach($product->prices->load('vendor') as $price)
<div class="my-2 border-bottom text-center">{{$price->vendor->name}} - {{$price->vendor->city}}</div>
	@foreach($price->data as $row)
	<div class="row">
		<div class="col text-center">{{$row['size']}}</div>
		<div class="col text-center">{{$row['cost']}}</div>
		<div class="col text-center">{{$row['resell']}}</div>
		<div class="col text-center">{{$row['retail']}}</div>
	</div>
	@endforeach
	<div class="row">
		<div class="col text-right">
			<a href="{{route('prices.edit',['price' => $price])}}" class="mr-2 text-info">修改</a>
			<a href="{{route('prices.destroy',['price' => $price])}}" class="mr-2 text-danger">删除</a>
		</div>
	</div>

@endforeach
</div>

@endsection
