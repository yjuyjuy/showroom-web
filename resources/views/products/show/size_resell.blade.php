<div class="row">
	<div class="col-12 text-center">
		调货价
	</div>
</div>
@foreach($product->getSizePrice('resell') as $size => $price)
<div class="row text-center no-gutters justify-content-center">
	<span class="col-3">{{ $size }}</span>
	<span class="col-3">-</span>
	<span class="col-3">&yen;{{$price}}</span>
</div>
@endforeach
