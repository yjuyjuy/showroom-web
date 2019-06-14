
@forelse($product->prices as $price)
<div class="py-4 my-2 mx-auto col-10 col-md-8 border">

	<div class="">{{ ($price->vendor->user_id == auth()->user()->id)?'我的报价':"{$price->vendor->name} - {$price->vendor->city}" }}</div>

	@foreach($price->data as $row)
	<div class="">{{ $row['size'] }} - &yen;{{ $row['resell'] }}</div>
	@endforeach


	<div class="text-right">
		<div class="d-inline mr-2">
			<a href="{{route('prices.edit',['price'=>$price])}}">修改</a>
		</div>
		<div class="d-inline">
			<a href="{{route('prices.destroy',['price'=>$price])}}">删除</a>
		</div>
	</div>

</div>

@empty

<div class="my-2 mx-auto col-10 col-md-8 text-center">
	<a href="{{route('prices.create',['product' => $product])}}">添加报价</a>
</div>

@endforelse
