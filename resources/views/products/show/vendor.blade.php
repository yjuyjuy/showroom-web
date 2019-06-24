<div class="row">
	<div class="font-weight-bold col">
		我的报价
	</div>
</div>
<div class="row">
	<span class="col">尺码</span>
	<span class="col">成本</span>
	<span class="col">调货</span>
	<span class="col">零售</span>
</div>
@foreach($product->prices->where('vendor_id',$vendor->id) as $price)
@foreach($price->data as $row)
<div class="row">
	<span class="col">{{ $row['size'] }}</span>
	<span class="col">&yen;{{$row['cost']}}</span>
	<span class="col">&yen;{{$row['resell']}}</span>
	<span class="col">&yen;{{$row['retail']}}</span>
</div>
@endforeach
<div class="row justify-content-md-end">
	<a href="{{route('prices.edit',['price'=>$price])}}" class="col-auto text-primary text-right pr-0">修改</a>
	<a href="#" class="col-auto text-danger" @click.prevent="deletePrice({{$price->id}})" >删除</a>
</div>
@endforeach
