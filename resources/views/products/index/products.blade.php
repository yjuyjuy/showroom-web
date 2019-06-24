<div class="">
	@forelse($products as $product)
	<div class="product    align-items-center">
		<div class="image">
			<a href="{{ route('products.show',['product' => $product->id ]) }}">
				<img class="" src="/storage/images/{{ $product->images->first()->filename ?? '1101182005_1_6.jpg' }}">
			</a>
		</div>
		<div class="text mt-n4">
			<div class=" ">
				<div class="brand text-left ">
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
	<div class="  ">
			no result
	</div>
	@endforelse
</div>
