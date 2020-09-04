<div class="d-flex flex-column products-show__info--properties">
	<div class="my-1">
		<span class="mdc-typography--headline6" style="text-transform: uppercase;">Gucci&nbsp;-&nbsp;</span>
		@if($product->category->parent ?? false)
		<a href="{{ route('gucci.categories.index',['category' => $product->category->parent]) }}"
			 class="mdc-typography--headline6">
			{{ $product->category->parent->translated_description }}
		</a>
		<span>&nbsp;-&nbsp;</span>
		@endif
		@if($product->category)
		<a href="{{ route('gucci.categories.index',['category' => $product->category]) }}"
			 class="mdc-typography--headline6">
			{{ $product->category->translated_description ?? '' }}
		</a>
		@endif
	</div>

	<div class="my-1">
		<span class="mdc-typography--headline6" style="text-transform: capitalize;">
			{{ $product->name }}
		</span>
		<span class="mdc-typography--headline6" style="text-transform: capitalize;">
			{{ __($product->color_material) }}
		</span>
	</div>
	<div class="my-1">
		<span>{{ $product->id }}</span>
	</div>

	<div class="my-2"></div>

	@if($product->description)
	<div class="my-1">
		{{ $product->description }}
	</div>
	@endif

	@if($product->detail)
	<div class="my-1">
		{!! $product->detail !!}
	</div>
	@endif
</div>
