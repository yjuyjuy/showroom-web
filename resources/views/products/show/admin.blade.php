@forelse($product->prices->loadMissing('vendor') as $price)
<div class="">
	<div class=" border-bottom  ">
		<a href="{{route('prices.edit',['price'=>$price])}}">
			{{$price->vendor->name.' - '.$price->vendor->city}}
		</a>
	</div>
	<div class="">
		@foreach($price->data as $row)
		<div class="">
			<span class="">{{ $row['size'] }}</span>
			<span class="">&yen;{{$row['cost']}}</span>
			<span class="">&yen;{{$row['resell']}}</span>
			<span class="">&yen;{{$row['retail']}}</span>
		</div>
		@endforeach
		<div class="">
			<a href="{{route('prices.edit',['price'=>$price])}}" class=" text-primary">修改</a>
			<a href="#" class=" text-danger" @click.prevent="deletePrice({{$price->id}})" >删除</a>
		</div>

	</div>
</div>

@empty
<div class="">
	<div class="">Not available</div>
</div>
@endforelse
<div class="">
	<div class="">
		<a href="#" class="btn btn-primary dropdown-toggle " data-toggle="dropdown" aria-haspopup="true" aria-expended="false">添加报价</a>
		<div class="dropdown-menu dropdown-menu-right">
			@foreach(\App\Vendor::whereNotIn('id',$product->prices->pluck('vendor_id')->toArray())->get() as $vendor)
			<a href="{{route('prices.create',['product' => $product, 'vendor' => $vendor->id])}}" class="dropdown-item">{{$vendor->name}}</a>
			@endforeach
		</div>
	</div>
</div>
