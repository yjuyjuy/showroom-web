@extends('layouts.app')

@section('title', __('Following vendors'))

@section('content')
<div class="d-flex flex-column">

	<form class="my-3 d-flex justify-content-center align-items-center">
		<div class="mdc-text-field mdc-text-field--outlined mdc-text-field--no-label">
			<input type="text" name="search" class="mdc-text-field__input" aria-label="Label" autofocus
			placeholder="搜索同行微信号" autocomplete="off">
			<div class="mdc-notched-outline">
				<div class="mdc-notched-outline__leading"></div>
				<div class="mdc-notched-outline__trailing"></div>
			</div>
		</div>
		<button type="submit" class="mdc-icon-button material-icons ml-2">
			<i class="material-icons">search</i>
		</button>
	</form>

	@if($message)
		<div class="my-3 text-center">{{ $message }}</div>
	@endif

	<div class="m-3 text-center">
		<span class="mdc-typography--headline6">注意: 成功关注后同行的调货价才会在页面显示，默认不关注任何人<br><br>点击下方名字查看对方所有库存，在【菜单 - 所有报价】页面合并查看所有已关注同行的库存</span>
	</div>

	<div class="my-3 d-flex justify-content-center flex-wrap">
		@if($vendors->isNotEmpty())
			<span class="mdc-typography--headline6 my-1 mx-3">已关注: </span>
			@foreach($vendors->shuffle() as $vendor)
				<a href="{{ route('vendor.products.index', compact('vendor')) }}" class="mdc-typography--headline6 my-1 mx-3">{{ $vendor->name }}</a>
			@endforeach
		@else
			<span class="mdc-typography--headline6">没有关注的同行</span>
		@endif

	</div>
</div>
@endsection
