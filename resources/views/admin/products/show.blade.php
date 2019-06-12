@extends('products.show')

@section('right-aside')
@parent
<div class="border mx-auto py-4 my-4 col-10 col-md-8">
	@if(!empty($sizes))
	@foreach($sizes as $size => $price)
	<div class="">{{ $size }} - &yen;{{$price}}</div>
	@endforeach
	@else
	<div class="">Currently not available</div>
	@endif
</div>
@endsection
