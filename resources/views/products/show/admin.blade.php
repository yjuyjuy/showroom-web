@forelse($product->prices->loadMissing('vendor') as $price)
<div class="row text-center no-gutters my-3">
	<div class="col-12 border-bottom pb-2 mb-2">
		<a href="{{route('prices.edit',['price'=>$price])}}">
			{{$price->vendor->name.' - '.$price->vendor->city}}
		</a>
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
		<div class="row text-center no-gutters justify-content-end">
			<a href="{{route('prices.edit',['price'=>$price])}}" class="col-3 text-primary">修改</a>
			<a href="#" class="col-3 text-danger" @click.prevent="deletePrice({{$price->id}})" >删除</a>
		</div>

	</div>
</div>

@empty
<div class="row text-center no-gutters my-3">
	<div class="col-12">Not available</div>
</div>
@endforelse
<div class="row">
	<div class="col-12">
		<a href="#" class="btn btn-primary dropdown-toggle w-100" data-toggle="dropdown" aria-haspopup="true" aria-expended="false">添加报价</a>
		<div class="dropdown-menu dropdown-menu-right">
			@foreach(\App\Vendor::whereNotIn('id',$product->prices->pluck('vendor_id')->toArray())->get() as $vendor)
			<a href="{{route('prices.create',['product' => $product, 'vendor' => $vendor->id])}}" class="dropdown-item">{{$vendor->name}}</a>
			@endforeach
		</div>
	</div>
</div>
