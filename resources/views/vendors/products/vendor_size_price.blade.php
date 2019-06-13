@if($product->prices->isNotEmpty())

@foreach($product->prices as $price)
<div class="py-4 my-4 mx-auto col-10 col-md-8 border">
	<div class=""><strong>{{$price->vendor->name}}</strong></div>
	@foreach($price->data as $size => $value)
	<div class="">{{ $size }} - &yen;{{$value}}</div>
	@endforeach
	<div class="text-right">
		<div class="d-inline mr-2">
			<a href="{{route('prices.edit',['price'=>$price])}}">edit</a>
		</div>
		<div class="d-inline">
			<a href="{{route('prices.destroy',['price'=>$price])}}">delete</a>
		</div>
	</div>
</div>
@endforeach
@else
<div class="py-4 my-4 mx-auto col-10 col-md-8 border">
	not available
</div>
@endif
