@extends('layouts.app')

@section('title', '修改尺码表 - '.$product->displayName())

@section('content')
<div class="container">
	<div class="">
		@include('products.show.images')
	</div>
	<div class="container__content">
		<form action="" method="post" id="update-form">
			@csrf
			@method('PATCH')
			<measurements-editor v-bind:input='@json($measurement->data)'></measurements-editor>
		</form>

		<div class="d-flex justify-content-end">
			<a href="#" class="mdc-button mdc-button--outlined" onclick="event.preventDefault(); window.history.back();">
				<span class="mdc-button__label">{{ __('Back') }}</span>
			</a>
			<button type="button" class="mdc-button mdc-button--outlined ml-2" form="update-form"
							onclick="var data = new FormData(this.form);
							axios.post('{{route('measurements.update',['product' => $product ])}}',data)
							.then(response=>{ window.location.replace(response.data.redirect) })">
				<span class="mdc-button__label">{{ __('submit') }}</span>
			</button>
		</div>
	</div>
</div>
@endsection
