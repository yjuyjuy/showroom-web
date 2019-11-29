<div class="d-flex flex-column products-show__info--properties">
	<div class="my-1">
		<span class="mdc-typography--headline6" style="text-transform: uppercase;">
			Balenciaga&nbsp;-&nbsp;
		</span>
		<a href="{{ route('balenciaga.departments.index',['department' => Str::slug($product->department)]) }}"
			 class="mdc-typography--headline6">
			 {{ __($product->department) }}
		</a>
	</div>

	<div class="my-1">
		<span class="mdc-typography--headline6" style="text-transform: capitalize;">
			{{ $product->name }}
		</span>
		<span class="mdc-typography--headline6" style="text-transform: capitalize;">
			{{ $product->description }}
		</span>
	</div>

	<div class="my-2"></div>

	@if($product->composition)
	<div class="my-1">
		{{ $product->composition }}
	</div>
	@endif

	@if($product->detail)
	<div class="my-1">
		{{ $product->detail }}
	</div>
	@endif
</div>
