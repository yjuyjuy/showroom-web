<div class="row text-center align-content-center">

	<div class="align-self-center col-12">
		<a href="{{ route('products.index') }}?brand[]={{$product->brand->id}}">{{ $product->brand->full_name }}</a>
	</div>

	<div class="align-self-center col-12">
		<span><a href="{{ route('products.index') }}?season[]={{$product->season->id}}">{{ $product->season->name }}</a> {{ $product->name_cn }}</span>
	</div>

	<div class="align-self-center col-12">
		<span>{{ $product->id }}</span>
	</div>

	@can('update',$product)
	<div class="align-self-center col-12">
		<a href="{{ route('products.edit',['product' => $product->id ]) }}">修改</a>
	</div>
	@endcan

</div>
