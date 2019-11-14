<div class="d-flex flex-column products-show__info--properties">
	<div class="my-1">
		<span class="mdc-typography--headline6" style="text-transform: uppercase;">
			Dior&nbsp;-&nbsp;
		</span>
		<a href="{{ route('dior.categories.index',['category' => Str::slug($product->category)]) }}"
			 class="mdc-typography--headline6">
			{{ __($product->gender) }}&nbsp;-&nbsp;{{ __($product->category) }}&nbsp;-&nbsp;{{ __($product->subcategory) }}
		</a>
	</div>

	<div class="my-1">
		<span class="mdc-typography--headline6" style="text-transform: capitalize;">
			{{ $product->name_cn }}
		</span>
		<span class="mdc-typography--headline6" style="text-transform: capitalize;">
			{{ __($product->color) }}
		</span>
	</div>

	<div class="my-2"></div>

	@if($product->description)
	<div class="my-1">
		{!! $product->description !!}
	</div>
	@endif
</div>
