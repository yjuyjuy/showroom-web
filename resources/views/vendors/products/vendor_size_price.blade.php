@if($product->prices->isNotEmpty())

@foreach($product->prices as $price)
<div class="py-4 my-4 mx-auto col-10 col-md-8 border">
	<div class=""> <strong>{{$price->vendor->name}}</strong> </div>
	@foreach($price->data as $size => $price)
	<div class="">{{ $size }} - &yen;{{$price}}</div>
	@endforeach
</div>
@endforeach
@else
@endif
