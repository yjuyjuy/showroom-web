<div class="">
	<div class="">
		<a href="{{ route('products.index') }}?brand[]={{$product->brand->id}}">{{ $product->brand->full_name }}</a>
	</div>
	<div class="">
		<span><a href="{{ route('products.index') }}?season[]={{$product->season->id}}">{{ $product->season->name }}</a> <strong>{{ $product->name_cn }}</strong></span>
	</div>
	<div class="">
		<span>{{ $product->id }}</span>
		@can('update',$product)
			<a href="{{route('products.edit', ['product' => $product])}}" class="">Edit</a>
		@endcan
	</div>
</div>
