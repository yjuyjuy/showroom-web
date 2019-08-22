@extends('layouts.app')
@section('title', '管理员 - '.$shop->name)

@section('content')
<div class="d-flex flex-column">
	<div class="text-center my-4"><a href="{{ route('taobao.products.index', ['shop' => $shop,]) }}" class="mdc-typography--headline5">店铺首页</a></div>
	<div class="text-center my-4"><a href="{{ route('taobao.admin.links', ['shop' => $shop,]) }}" class="mdc-typography--headline5">未关联商品</a></div>
	<div class="text-center my-4"><a href="{{ route('taobao.admin.diffs', ['shop' => $shop,]) }}" class="mdc-typography--headline5">检测价格</a></div>
	<div class="text-center my-4"><a href="{{ route('taobao.admin.linked', ['shop' => $shop,]) }}" class="mdc-typography--headline6">已关联商品</a></div>
	<div class="text-center my-4"><a href="{{ route('taobao.admin.ignored', ['shop' => $shop,]) }}" class="mdc-typography--headline6">已忽略商品</a></div>
</div>
@endsection