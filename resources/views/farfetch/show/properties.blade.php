<div class="d-flex flex-column products-show__info--properties">
	<div class="my-1">
		<a href="{{ route('farfetch.designers.index',['designer' => $product->designer]) }}"
			 class="mdc-typography--headline6" style="text-transform: uppercase;">
			{{ $product->designer->description }}
		</a>

		<a href="{{ route('farfetch.categories.index',['category' => $product->category]) }}"
			 class="mdc-typography--headline6">
			{{ $product->category->description }}
		</a>
	</div>
	<div class="my-1">
		<span class="mdc-typography--headline6" style="text-transform: capitalize;">
			{{ $product->short_description }}
		</span>

		<span class="mdc-typography--headline6" style="text-transform: capitalize;">
			{{ $product->colors }}
		</span>
	</div>
	<div class="my-1">
		<span>{{ $product->designer_style_id }}</span>
	</div>

	<div class="my-2"></div>

	@if($product->composition)
	<div class="my-1">
		<span>材质: {{ $product->composition }}</span>
	</div>
	@endif
	@if($product->model_is_wearing)
	<div class="my-1">
		<span>模特所穿尺码为{{ $product->model_is_wearing }}</span>
	</div>
	@endif
	@if($product->model_measurements)
	<div class="my-1">
		<span>模特资料: {{ $product->model_measurements }}</span>
	</div>
	@endif
</div>
