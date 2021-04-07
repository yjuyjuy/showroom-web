@extends('layouts.app')

@section('title', __('Select Language'))

@section('content')
<div class="fullscreen-center">
	<div class="d-flex flex-column">
		<div class="text-center my-3">
            <form method="POST" action="{{ route('language.update') }}">
                @csrf
                <input type="hidden" name="language" value="zh">
			    <a onclick="this.parentElement.submit()" href="#" class="mdc-typography--headline5">中文</a>
            </form>
		</div>
		<div class="text-center my-3">
            <form method="POST" action="{{ route('language.update') }}">
                @csrf
                <input type="hidden" name="language" value="en">
			    <a onclick="this.parentElement.submit()" href="#" class="mdc-typography--headline5">English</a>
            </form>
		</div>
	</div>
</div>
@endsection
