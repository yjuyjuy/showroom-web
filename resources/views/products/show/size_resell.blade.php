<div class="">
	<div class="font-weight-bold">
		调货价
	</div>
</div>
@foreach($product->getSizePrice('resell') as $size => $price)
<div class="">
	<span class="">{{ $size }}</span>
	<span class="">-</span>
	<span class="">&yen;{{$price}}</span>
</div>
@endforeach
