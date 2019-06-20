@forelse($product->prices->load('vendor') as $price)
<div class="row text-center no-gutters my-3">
	<div class="col-12 border-bottom pb-2 mb-2">
		{{$price->vendor->name.' - '.$price->vendor->city}}
	</div>
	<div class="col-12">
		@foreach($price->data as $row)
		<div class="row text-center no-gutters">
			<span class="col-3">{{ $row['size'] }}</span>
			<span class="col-3">&yen;{{$row['cost']}}</span>
			<span class="col-3">&yen;{{$row['resell']}}</span>
			<span class="col-3">&yen;{{$row['retail']}}</span>
		</div>
		@endforeach
	</div>
</div>

@empty
<div class="row text-center no-gutters">
	<div class="col-12">Currently not available</div>
</div>
@endforelse
