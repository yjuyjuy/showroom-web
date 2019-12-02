@extends('layouts.app')

@section('title', '注册成功')

@section('content')
<div class="fullscreen-center">
	<div class="d-flex flex-column">
		<div class="text-center my-5">
			<a href="{{ route('following.retailers') }}" class="mdc-typography--headline5">我是买家</a>
		</div>
		<div class="text-center my-5">
			<a href="{{ route('account.status') }}" class="mdc-typography--headline5">我是卖家</a>
		</div>
	</div>
</div>
@endsection
