@extends('layouts.app')

@section('title', '所有店铺 - 淘宝管理')

@section('content')
<div class="fullscreen-center">
	<div class="d-flex flex-column mt-n4">
		@foreach($shops->shuffle() as $shop)
		<div class="text-center my-4">
			<a href="{{ route('taobao.admin', ['shop' => $shop,]) }}" class="mdc-typography--headline5">{{ $shop->name }}</a>
		</div>
		@endforeach
	</div>
</div>
@endsection
