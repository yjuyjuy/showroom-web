@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-12 col-md-6">
			<div class="row">
				@foreach($product->images as $image)
				<div class="col-6 mb-3">
					<img class="w-100" src="/storage/images/{{ $image->filename }}">
				</div>
				@endforeach

			</div>
		</div>
		<div class="col-12 col-md-6">
			<div class="col-12 my-3">
				<div class="row text-center align-content-center">
					<div class="col-12">
						<a href="{{ route('products.index') }}?brand[]={{$product->brand->id}}">{{ $product->brand->full_name }}</a>
					</div>
					<div class="col-12">
						<span><a href="{{ route('products.index') }}?season[]={{$product->season->id}}">{{ $product->season->name }}</a> {{ $product->name_cn }}</span>
					</div>
					<div class="col-12">
						<span>{{ $product->id }}</span>
						<a class="text-info ml-2" href="{{ route('products.edit',['product' => $product->id ]) }}">修改</a>
					</div>
				</div>
			</div>
			<div class="py-4 my-2 col-12 border">
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

			<div class="py-4 my-2 col-12 border">
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
						<a href="{{route('prices.edit',['price' => $price])}}" class="mr-2 btn btn-sm btn-info">修改</a>
						<form class="d-inline" action="{{route('prices.destroy',['price' => $price])}}" method="post">
							@csrf
							@method('DELETE')
							<button class="mr-2 btn btn-danger btn-sm" type="submit">删除</button>
						</form>
					</div>
				</div>

				@endforeach
			</div>


		</div>
	</div>
</div>
@endsection
