<div class="py-4 my-4 mx-auto col-10 col-md-8 border">
	@if($product->prices->isNotEmpty())
	@foreach($product->size_price as $size => $price)
	<div class="">{{ $size }} - &yen;{{$price}}</div>
	@endforeach
	@else
	<div class="">Currently not available</div>
	@endif
</div>
