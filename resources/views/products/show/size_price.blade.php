@forelse($product->size_price as $size => $price)
<div class="row text-center no-gutters">
	<span class="col-4">{{ $size }}</span>
	<span class="col-4"> - </span>
	<span class="col-4">&yen;{{$price}}</span>
</div>
@empty
<div class="text center">Currently not available</div>
@endforelse
