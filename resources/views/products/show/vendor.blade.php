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
@foreach($product->prices->where('vendor_id',$vendor->id) as $price)
@foreach($price->data as $row)
<div class="row text-center no-gutters">
	<span class="col-3">{{ $row['size'] }}</span>
	<span class="col-3">&yen;{{$row['cost']}}</span>
	<span class="col-3">&yen;{{$row['resell']}}</span>
	<span class="col-3">&yen;{{$row['retail']}}</span>
</div>
@endforeach
<div class="row text-center no-gutters justify-content-end">
	<a href="{{route('prices.edit',['price'=>$price])}}" class="col-3 text-primary">修改</a>
	<a href="#" class="col-3 text-danger" @click.prevent="deletePrice({{$price->id}})" >删除</a>
</div>
@endforeach
