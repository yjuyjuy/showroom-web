@extends('products.show')

@section('right-aside')
<div class="py-2 mx-auto col-10 col-md-8 text-center border-top border-bottom">
		<div class="">
			零售价格
		</div>
</div>
@include('products.size_price')

<div class="py-2 mx-auto col-10 col-md-8 text-center border-top border-bottom">
		<div class="">
			调货价格
		</div>
</div>

@include('vendors.products.vendor_size_price')
@endsection
