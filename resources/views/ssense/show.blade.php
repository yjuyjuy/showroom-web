@extends('layouts.app')

@section('title', $product->brand_name.' '.$product->name.' - SSENSE')

@section('content')
<div class="container">
	<div class="">
		@include('ssense.show.images')
	</div>

	<div class="container__content d-flex flex-column">
		@include('ssense.show.properties')
		@include('ssense.show.customer')

		@if($product->product_measurements)
		<?php $measurements = $product->product_measurements;
					$sizes = array_keys(array_values($measurements)[0]);
					uasort($sizes, function($a, $b) {
						$sizes = ['XXS','XS','S','M','L','XL','XXL','XXXL'];
						if (in_array($a, $sizes)) { $a = array_search($a, $sizes); }
						if (in_array($b, $sizes)) { $b = array_search($b, $sizes); }
						return $a > $b;
					})?>
		<div class="my-3 text-right">
				<div class="d-flex">
					<div class="col p-1 text-center" style="width:10rem;">{{ __('Size') }}</div>
					@foreach(array_keys($measurements) as $column_name)
					<div class="col p-1">{{ __($column_name) }}</div>
					@endforeach
				</div>
				@foreach($sizes as $size)
					<div class="d-flex">
						<div class="col p-1 text-center" style="width:10rem;">{{ $size }}</div>
						@foreach($measurements as $column_name => $values)
						<div class="col p-1">{{ $values[$size]['cm'] ?? '' }}</div>
						@endforeach
					</div>
				@endforeach
		</div>
		@endif
	</div>
</div>
@include('layouts.back_fab')
@endsection
