<div class="row">
	<div class="col-12 text-center">
		我的报价
	</div>
</div>
<div class="row text-center no-gutters">
	<span class="col-3">尺码</span>
	<span class="col-3">成本</span>
	<span class="col-3">调货</span>
	<span class="col-3">零售</span>
</div>
@foreach($product->prices->firstWhere('vendor_id',$vendor->id)->data as $row)
<div class="row text-center no-gutters">
	<span class="col-3">{{ $row['size'] }}</span>
	<span class="col-3">&yen;{{$row['cost']}}</span>
	<span class="col-3">&yen;{{$row['resell']}}</span>
	<span class="col-3">&yen;{{$row['retail']}}</span>
</div>
@endforeach
