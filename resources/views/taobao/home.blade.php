@extends('layouts.app')

@section('title','我的淘宝 - '.$shop->name)

@section('content')
<div class="d-flex flex-column">
	<div class="text-center my-4">
		<a href="{{ route('taobao.index',['shop' => $shop,]) }}" class="mdc-typography--headline5">店铺首页</a>
	</div>
	<div class="text-center my-4">
		<a href="{{ route('taobao.prices.diffs',['shop' => $shop,]) }}" class="mdc-typography--headline5">价格变动</a>
	</div>
	<div class="text-center my-4">
		<a href="{{ route('taobao.products.manage',['shop' => $shop,]) }}" class="mdc-typography--headline5">关联商品</a>
	</div>
	<div class="text-center my-4">
		<a href="{{ route('taobao.products.reset',['shop' => $shop,]) }}" class="mdc-typography--headline5">重置商品关联</a>
	</div>
	@if(!$shop->is_partner)
	<div class="text-center my-4">
		<a href="{{ route('taobao.prices.reset',['shop' => $shop,]) }}" class="mdc-typography--headline5">重新计算价格</a>
	</div>
	@endif
</div>
@endsection
