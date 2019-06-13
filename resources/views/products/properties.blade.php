<div class="d-flex flex-column text-center justify-content-center vh-100">

	<div class="align-self-center mt-n5">
		<a href="{{ route('products.index') }}?brand[]={{$product->brand->id}}">{{ $product->brand->full_name }}</a>
	</div>

	<div class="align-self-center">
		<span><a href="{{ route('products.index') }}?season[]={{$product->season->id}}">{{ $product->season->name }}</a> {{ $product->name_cn }}</span>
	</div>

	<div class="align-self-center">
		<span>{{ $product->id }}</span>
	</div>

	@can('update',$product)
	<div class="align-self-center">
		<a href="{{ route('products.edit',['product' => $product->id ]) }}">修改</a>
	</div>
	@endcan

</div>
