<div class="row">
	@forelse($products as $product)
	<div class="product col-6 col-md-4 align-items-center mb-2">
		<div class="image">
			<a href="{{ route('products.show',['product' => $product->id ]) }}">
				<img class="w-100" src="/storage/images/{{ $product->images->first()->filename ?? '1101182005_1_6.jpg' }}">
			</a>
		</div>
		<div class="text mt-n4">
			<div class="">
				<div class="brand text-left ">
					<a href="{{ route('products.show',['product' => $product->id ]) }}" class="text-decoration-none">{{ $product->brand->name }}</a>
				</div>
				<div class="name">
					<a href="{{ route('products.show',['product' => $product->id ]) }}" class="text-decoration-none">{{ $product->name_cn }}</a>
				</div>
				<div class="price">
					@if($product->price)
					<a href="{{ route('products.show',['product' => $product ]) }}" class="">
						{{ "\u{00a5}".$product->price }}
					</a>
					@else
					<a href="{{ route('products.show',['product' => $product ]) }}" class="text-primary">
						缺货
					</a>
					@endif
				</div>
			</div>
		</div>
	</div>
	@empty
	<div class="">
		no result
	</div>
	@endforelse
</div>
