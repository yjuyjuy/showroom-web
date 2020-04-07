<div class="d-flex flex-column products-show__info--properties">

	<div class="mdc-typography--headline6">
		<a id="product-brand" href="{{ route('products.index',['brand' => [$product->brand_id,]]) }}">
			{{ __($product->brand->full_name ?? '') }}</a>
		<a id="product-brand" href="{{ route('products.index',['category' => [$product->category_id,]]) }}">
			{{ __($product->category->name ?? '') }}</a>
	</div>
	<div class="mdc-typography--headline6">
		<a class="product-name" href="{{ route('products.show',['product' => $product]) }}">
			{{ $product->name_cn }}
		</a>
	</div>

	<div class="mt-2">
		<div style="text-transform:capitalize;">{{ $product->name }} {{ __($product->color->name ?? '') }}</div>
		<div class="">
			@if($product->season_id)<span class="">{{$product->season->name}} {{ __('season') }}</span>@endif
			@if($product->designer_style_id)<span class="">{{ __('designer_style_id') }}: {{$product->designer_style_id}}</span>@endif
		</div>
		@can('update',$product)
		<div class="">
			<span class="">ID: {{ $product->id }}</span>
			<a href="{{ route('products.edit',['product' => $product ]) }}">{{ __('edit') }}</a>
		</div>
		@endcan
	</div>

	<div class="d-flex justify-content-end">
		@if($product->measurement)
			<button type="button" class="mdc-button" data-target="size-chart" onclick="
				event.preventDefault()
				var el = document.getElementById(this.dataset.target);
				if (el.style.display != 'none') el.style.display = 'none';
				else el.style.display = 'block';">
				<span class="mdc-button__label">{{ __('Size chart') }}</span>
				<i class="material-icons mdc-button__icon" aria-hidden="true">arrow_drop_down</i>
			</button>
		@elseif($user && $user->vendor)
			<a href="{{ route('measurements.create', ['product' => $product,]) }}" class="mdc-button">
				<span class="mdc-button__label">{{ __('Add size chart') }}</span>
			</a>
		@endif

		@if(!empty($product->links))
			<div class="mdc-menu-surface--anchor ml-3">
				<button type="button" class="mdc-button open-menu-button">
					<span class="mdc-button__label">官网页面</span>
					<i class="material-icons mdc-button__icon" aria-hidden="true">arrow_drop_down</i>
				</button>
				<div class="mdc-menu mdc-menu-surface mdc-menu--with-button">
					<ul class="mdc-list" role="menu" aria-hidden="true" aria-orientation="vertical" tabindex="-1">
						@foreach($product->links as $description => $url)
							<a href="{{ $url }}" class="mdc-list-item mdc-list-item__text text-left" role="menuitem">{{ $description }}</a>
						@endforeach
					</ul>
				</div>
			</div>
		@endif
	</div>

	@if($product->measurement)
	<div class="d-flex justify-content-end">
		<?php
			$data = $product->measurement->data;
			$fields = array_keys($data);
			$sizes = [];
			foreach($data as $field => $values){
				foreach($values as $size => $value) {
					if (!in_array($size, $sizes)) $sizes[] = (String)$size;
				}
			}
			$SML = ['XXS', 'XS', 'S', 'M', 'L', 'XL', 'XXL', 'XXXL'];
			usort($sizes, function($a, $b) use ($SML) {
				if (in_array($a, $SML)) {
					$a = array_search($a, $SML);
				}
				if (in_array($b, $SML)) {
					$b = array_search($b, $SML);
				}
				return ($a < $b) ? -1 : 1;
			});
		?>
		<div class="mdc-data-table my-3" id="size-chart" style="display:none;">
			<table class="mdc-data-table__table">
				<thead>
					<tr class="mdc-data-table__header-row">
						@if($user && $user->vendor)
							<th class="mdc-data-table__header-cell" role="columnheader" scope="col"><a href="{{ route('measurements.edit', ['product' => $product,]) }}">{{ __('edit') }}</a></th>
						@else
							<th class="mdc-data-table__header-cell" role="columnheader" scope="col"></th>
						@endif
						@foreach($fields as $field)
							<th class="mdc-data-table__header-cell" role="columnheader" scope="col">
								{{ $field }}</th>
						@endforeach
					</tr>
				</thead>
				<tbody class="mdc-data-table__content">
					@foreach($sizes as $size)
						<tr class="mdc-data-table__row">
							<td class="mdc-data-table__cell">{{ $size }}</td>
							@foreach($fields as $field)
								<td class="mdc-data-table__cell">
									@if(array_key_exists($size, $data[$field]))
										{{ $data[$field][$size] }}
									@endif
								</td>
							@endforeach
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
	@endif
</div>
