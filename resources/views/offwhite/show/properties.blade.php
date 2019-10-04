<div class="d-flex flex-column products-show__info--properties">
	<div class="my-1">
		<span class="mdc-typography--headline6">OFF-WHITE C/O VIRGIL ABLOH</span>
		 -
		<a href="{{ route('offwhite.categories.index',['category' => $product->category]) }}"
			 class="mdc-typography--headline6">
			{{ strtoupper($product->category) }}
		</a>
	</div>
	<div class="my-1">
		<span class="mdc-typography--headline6" style="text-transform: capitalize;">
			{{ $product->name }}
		</span>
	</div>
	<div class="my-1">
		<span>{{ $product->id }}</span>
	</div>
</div>
