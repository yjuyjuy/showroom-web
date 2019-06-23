<div class="row">
	@forelse($products as $product)
	<div class="product col-6 col-md-3 flex-column align-items-center">
		<div class="image">
			<a href="{{ route('products.show',['product' => $product->id ]) }}">
				<img class="w-100" src="/storage/images/{{ $product->images->first()->filename ?? '1101182005_1_6.jpg' }}">
			</a>
		</div>
		<div class="text mt-n4">
			<div class="flex-column text-center">
				<div class="brand text-left pl-3">
					<a href="{{ route('products.show',['product' => $product->id ]) }}" class="text-decoration-none">{{ $product->brand->name }}</a>
				</div>
				<div class="name">
					<a href="{{ route('products.show',['product' => $product->id ]) }}" class="text-decoration-none">{{ $product->name_cn }}</a>
				</div>
				<div class="price">
					<a href="{{ route('products.show',['product' => $product ]) }}">
						{{ ($product->price)?"\u{00a5}".$product->price:'-' }}
					</a>
				</div>
			</div>
		</div>
	</div>
	@empty
	<div class="col-12 text-center my-5">
			no result
	</div>
	@endforelse
</div>