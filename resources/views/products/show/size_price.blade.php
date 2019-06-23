@forelse($product->size_price as $size => $price)
<div class="row text-center no-gutters justify-content-center">
	<span class="col-3">{{ $size }}</span>
	<span class="col-3">-</span>
	<span class="col-3">&yen;{{$price}}</span>
</div>
@empty
<div class="w-100 text-center text-muted mt-2">not available</div>
@endforelse