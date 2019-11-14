<div class="d-flex flex-column products-show__info--properties">
	<div class="my-1">
		<span class="mdc-typography--headline6" style="text-transform: uppercase;">
			Louis&nbsp;Vuitton&nbsp;-&nbsp;
		</span>
		<a href="{{ route('louisvuitton.categories.index',['category' => Str::slug($product->category)]) }}"
			 class="mdc-typography--headline6">
			{{ __($product->gender) }}&nbsp;-&nbsp;{{ __($product->category) }}&nbsp;-&nbsp;{{ __($product->subcategory) }}
		</a>
	</div>

	<div class="my-1">
		<span class="mdc-typography--headline6" style="text-transform: capitalize;">
			{{ $product->name }}
		</span>
		<span class="mdc-typography--headline6" style="text-transform: capitalize;">
			{{ __($product->color) }}
		</span>
	</div>
	<div class="my-1">
		<span>{{ $product->id }}</span>
	</div>

	<div class="my-2"></div>

	@if($product->description)
	<div class="my-1">
		{!! $product->description !!}
	</div>
	@endif

	@if($product->detail)
	<div class="my-1">
		{{ $product->detail }}
	</div>
	@endif
</div>
