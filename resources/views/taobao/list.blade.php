@extends('layouts.app')

@section('title','所有淘宝店铺')

@section('content')
<div class="d-flex flex-column">
	@foreach($shops as $shop)
	<div class="text-center my-4">
		<a href="{{ route('taobao.index',['shop' => $shop,]) }}" class="mdc-typography--headline5">{{ $shop->name }}</a>
	</div>
	@endforeach
</div>
@endsection
