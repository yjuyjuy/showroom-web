@extends('layouts.index')

@section('center')
<div class="container-fluid">
	<div class="row">

	@foreach($products as $product)
	<div class="product col-6 col-sm-4 col-md-2 flex-column align-items-center">

		<div class="image">
			<a href="/product/{{$product->id}}">
				<img class="w-100" src="/storage/images/{{ $product->images->first()->filename ?? '' }}">
			</a>
		</div>

		<div class="text mt-n4">
			<a href="/product/{{ $product->id }}">
				<div class="flex-column text-center">

					<div class="brand text-left pl-3">
						<span>{{ $product->brand->name }}</span>
					</div>

					<div class="name">
						<span>{{ $product->name_cn }}</span>
					</div>

					<div class="price">
						<span>{{ $product->minPrice() }}</span>
					</div>

				</div>
			</a>
		</div>

	</div>
	<!-- <div class="product col d-flex flex-column">
		<a href="/product/1101191001">
			<div class="image">
					<img class="w-100" src="/storage/images/1101191001_1_1.jpg">
			</div>

			<div class="text mt-n4 d-flex flex-column text-center">

				<div class="brand text-left pl-4">
					<span>Off-White</span>
				</div>
				<div class="name">
					<span>19ss 油画箭头短袖</span>
				</div>
				<div class="price">
					<span>&yen;1380</span>
				</div>

			</div>
		</a>
	</div> -->
	@endforeach
  </div>

	<div class="row justify-content-center">
	  {{ $products->onEachSide(2)->links() }}
	</div>
</div>

@endsection
