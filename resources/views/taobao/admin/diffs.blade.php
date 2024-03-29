@extends('layouts.app')

@section('title', '价格同步 - 淘宝管理')

@section('content')
<div class="mx-2">
	@foreach($diffs->where('retail', null)->where('taobao', '!=', null) as $diff)
		<div class="d-flex diff-card my-4">
			<div class="diff-card__image" style="width:20%;">
				<img class="lazy" data-src="{{ $diff['product']->image->url ?? '' }}" alt="">
			</div>
			<div class="d-flex flex-column price-card col">
				<a href="{{ $diff['taobao']->url }}" target="_blank">{{ $diff['taobao']->description }}</a>
				@foreach($diff['taobao']->prices as $size => $price)
				<span class="size-price">{{ $size }} - &yen;{{ $price }}</span>
				@endforeach
			</div>
			<div class="d-flex flex-column price-card col">
				<a href="{{ route('products.show',['product' => $diff['product'],]) }}" target="_blank">没有找到报价 (点击添加)</a>
			</div>
		</div>
	@endforeach
	@foreach($diffs->where('taobao', null)->where('retail', '!=', null) as $diff)
		<div class="d-flex diff-card my-4">
			<div class="diff-card__image" style="width:20%;">
				<img class="lazy" data-src="{{ $diff['product']->image->url ?? '' }}" alt="">
			</div>
			<div class="d-flex flex-column price-card col">
				<a href="https://router.publish.taobao.com/router/publish.htm" target="_blank">没有找到商品 (点击上架)</a>
			</div>
			<div class="d-flex flex-column price-card col">
				<a href="{{ route('products.show',['product' => $diff['retail']->product,]) }}">{{ $diff['product']->displayName().'-'.__($diff['product']->color->name) }}</a>
				@foreach($diff['retail']->prices as $size => $price)
				<span class="size-price">{{ $size }} - &yen;{{ $price }}</span>
				@endforeach
			</div>
		</div>
	@endforeach
	@foreach($diffs->where('retail', '!=', null)->where('taobao', '!=', null) as $diff)
		<div class="d-flex diff-card my-4">
			<div class="diff-card__image" style="width:20%;">
				<img class="lazy" data-src="{{ $diff['product']->image->url ?? '' }}" alt="">
			</div>
			<div class="d-flex flex-column price-card col">
				<a href="{{ $diff['taobao']->url }}" target="_blank">{{ $diff['taobao']->description }}</a>
				@foreach($diff['taobao']->prices as $size => $price)
				<span class="size-price">{{ $size }} - &yen;{{ $price }}</span>
				@endforeach
			</div>
			<div class="d-flex flex-column price-card col">
				<a href="{{ route('products.show',['product' => $diff['retail']->product,]) }}">{{ $diff['product']->displayName().'-'.__($diff['product']->color->name) }}</a>
				@foreach($diff['retail']->prices as $size => $price)
				<span class="size-price">{{ $size }} - &yen;{{ $price }}</span>
				@endforeach
			</div>
		</div>
	@endforeach
</div>
@endsection
