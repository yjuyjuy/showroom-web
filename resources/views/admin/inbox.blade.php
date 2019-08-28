@extends('layouts.app')

@section('title', '手动执行程序 - admin')

@section('content')
<div class="fullscreen-center">
	<div class="d-flex flex-column text-center mt-n4">
		<div class="text-center my-4"><a href="{{ route('admin.inbox.requests') }}" class="mdc-typography--headline5">升级账户申请</a></div>
		<div class="text-center my-4"><a href="{{ route('admin.inbox.suggestions') }}" class="mdc-typography--headline5">功能建议</a></div>
	</div>
</div>
@endsection
