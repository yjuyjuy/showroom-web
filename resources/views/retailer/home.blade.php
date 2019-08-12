@extends('layouts.app')

@section('title', $retailer->name.' '.__('dashboard'))

@section('content')
<div class="m-2">
	<div class="w-100">
		<span class="mdc-typography--headline5">{{ $retailer->name }}</span>
	</div>
	<div class="w-50">
		@include('retailer.home.products')
	</div>
	<div class="w-50">
		<!-- @include('retailer.home.prices') -->
	</div>
</div>
@endsection
