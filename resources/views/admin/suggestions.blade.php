@extends('layouts.app')

@section('title', '收到的功能建议 - admin')

@section('content')
<div class="d-flex justify-content-center mt-4 mx-3">
	<div class="d-flex flex-column">
		@forelse($suggestions as $suggestion)
		<div class="my-4">
			<p class="my-2 mdc-typography--headline5">
				{{ $suggestion->title }}
			</p>
			<p class="my-2 mdc-typography--headline6">
				{{ $suggestion->content }}
			</p>
			<div class="text-right">
				<button type="button" class="mdc-button">
					<span class="mdc-button__label">忽略</span>
				</button>
				<button type="button" class="mdc-button">
					<span class="mdc-button__label">采纳</span>
				</button>
			</div>
		</div>
		@empty
		<div class="">
			<span class="mdc-typography--headline5">没有新的建议</span>
		</div>
		@endforelse
	</div>
</div>
@endsection
