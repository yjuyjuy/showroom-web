@extends('layouts.app')

@section('title', '收到的申请 - 管理员功能')

@section('content')
<div class="d-flex justify-content-center mt-4 mx-3">
	<div class="d-flex flex-column mx-3 w-100" style="max-width:600px;">
		<div class="m-3 mdc-typography--headline5">待处理用户升级账户申请</div>
		@foreach($users as $user)
		<div class="m-3">
			<div class="my-1 mdc-typography--headline6">用户名: {{$user->username}}</div>
			<div class="my-1 mdc-typography--headline6">微信号: {{$user->wechat_id}}</div>
			<div class="text-right my-1">
				<button type="button" class="mdc-button" onclick="window.axios.post('requests/agree/{{ $user->id }}').then(response => window.location.reload()).catch(error => window.alert('action failed'));">
					<span class="mdc-button__label">同意</span>
				</button>
				<button type="button" class="mdc-button mdc-button--error"  onclick="window.axios.post('requests/reject/{{ $user->id }}').then(response => window.location.reload()).catch(error => window.alert('action failed'));">
					<span class="mdc-button__label">拒绝</span>
				</button>
			</div>
		</div>
		@endforeach
	</div>
</div>
@endsection
