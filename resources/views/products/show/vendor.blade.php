<div class="">
	<div class=" ">
		我的报价
	</div>
</div>
<div class="  ">
	<span class="">尺码</span>
	<span class="">成本</span>
	<span class="">调货</span>
	<span class="">零售</span>
</div>
@foreach($product->prices->where('vendor_id',$vendor->id) as $price)
@foreach($price->data as $row)
<div class="  ">
	<span class="">{{ $row['size'] }}</span>
	<span class="">&yen;{{$row['cost']}}</span>
	<span class="">&yen;{{$row['resell']}}</span>
	<span class="">&yen;{{$row['retail']}}</span>
</div>
@endforeach
<div class="   ">
	<a href="{{route('prices.edit',['price'=>$price])}}" class=" text-primary">修改</a>
	<a href="#" class=" text-danger" @click.prevent="deletePrice({{$price->id}})" >删除</a>
</div>
@endforeach
