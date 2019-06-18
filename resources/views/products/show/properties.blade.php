<div class="d-flex flex-column text-center">
	<div class="">
		<a href="{{ route('products.index') }}?brand[]={{$product->brand->id}}">{{ $product->brand->full_name }}</a>
	</div>
	<div class="">
		<span><a href="{{ route('products.index') }}?season[]={{$product->season->id}}">{{ $product->season->name }}</a> {{ $product->name_cn }}</span>
	</div>
	<div class="">
		<span>{{ $product->id }}</span>
	</div>
</div>
