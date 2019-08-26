@extends('layouts.app')

@section('title', __('Following vendors'))

@section('content')
<div class="d-flex flex-column">

	<form class="my-5 d-flex justify-content-center align-items-center">
		<div class="mdc-text-field mdc-text-field--outlined mdc-text-field--no-label">
			<input type="text" name="wechat_id" class="mdc-text-field__input" aria-label="Label" autofocus
			placeholder="输入微信号搜索">
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
		<div class="mb-5 text-center">{{ $message }}</div>
	@endif

	<div class="my-3 d-flex justify-content-center flex-wrap">
		@if($vendors->isNotEmpty())
			<span class="mdc-typography--headline6 my-1 mx-3">已关注: </span>
			@foreach($vendors->shuffle() as $vendor)
				<a href="{{ route('vendor.products.index', compact('vendor')) }}" class="mdc-typography--headline6 my-1 mx-3">{{ $vendor->name }}</a>
			@endforeach
		@else
			<span>没有关注的同行</span>
		@endif

	</div>
</div>
@endsection
