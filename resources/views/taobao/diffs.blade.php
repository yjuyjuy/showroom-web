@extends('layouts.app')

@section('title', $shop->name.' - 管理中心')

@section('content')
<div class="mx-2">
	@foreach($diffs as $diff)
		<div class="d-flex diff-card my-4">
			<div class="diff-card__image" style="width:20%;">
				<img src="{{ $diff['product']->image->url ?? asset('storage/icons/ImagePlaceholder.svg') }}" alt="">
			</div>
			<div class="d-flex flex-column price-card col">
				@if($diff['taobao'])
					<a href="{{ $diff['taobao']->url }}" target="_blank">{{ $diff['taobao']->description }}</a>
					@foreach($diff['taobao']->prices as $size => $price)
					<span class="size-price">{{ $size }} - &yen;{{ $price }}</span>
					@endforeach
				@else
					<a href="https://router.publish.taobao.com/router/publish.htm" target="_blank">没有找到商品 (点击上架)</a>
				@endif
			</div>
			<div class="d-flex flex-column price-card col">
				@if($diff['retail'])
					<a href="{{ route('products.show',['product' => $diff['retail']->product,]) }}" target="_blank">{{ $diff['product']->displayName().'-'.__($diff['product']->color->name) }}</a>
					@foreach($diff['retail']->prices as $size => $price)
					<span class="size-price">{{ $size }} - &yen;{{ $price }}</span>
					@endforeach
				@else
					<a href="{{ route('prices.create',['product' => $diff['product'],]) }}" target="_blank">没有找到报价 (点击添加)</a>
				@endif
			</div>
		</div>
	@endforeach
</div>
@endsection
