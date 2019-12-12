<div class="d-flex">
	<div class="mdc-menu-surface--anchor">
		<button type="button" class="mdc-button open-menu-button"><span class='mdc-button__label'>{{ __('category') }}</span></button>
		<div class="mdc-menu mdc-menu-surface mdc-menu--with-button">
			<ul class="mdc-list" role="menu" aria-hidden="true" aria-orientation="vertical" tabindex="-1">
				@if(old('category'))
				<a href="#" class="mdc-list-item mdc-list-item__text" role="menuitem" data-target="input[name='category[]']" onclick="
					event.preventDefault();
					var target = document.querySelector(this.dataset.target);
					[].map.call(document.querySelectorAll('input[name=\'' + target.name + '\']'), (el)=>{el.checked=false;});
					target.form.submit();">所有分类</a>
				@endif
				@foreach($filters['category'] as $value)
					<a href="#" class="mdc-list-item mdc-list-item__text" role="menuitem" data-target="#filter-category-{{$value->id}}-checkbox" onclick="
						event.preventDefault();
						var target = document.querySelector(this.dataset.target);
						[].map.call(document.querySelectorAll('input[name=\'' + target.name + '\']'), (el)=>{el.checked=false;});
						target.checked = true;
						target.form.submit();">{{ __($value->name) }}</a>
				@endforeach
			</ul>
		</div>
	</div>
	<div class="mdc-menu-surface--anchor">
		<button type="button" class="mdc-button open-menu-button"><span class='mdc-button__label'>{{ __('brand') }}</span></button>
		<div class="mdc-menu mdc-menu-surface mdc-menu--with-button">
			<ul class="mdc-list" role="menu" aria-hidden="true" aria-orientation="vertical" tabindex="-1">
				@if(old('brand'))
				<a href="#" class="mdc-list-item mdc-list-item__text" role="menuitem" data-target="input[name='brand[]']" onclick="
					event.preventDefault();
					var target = document.querySelector(this.dataset.target);
					[].map.call(document.querySelectorAll('input[name=\'' + target.name + '\']'), (el)=>{el.checked=false;});
					target.form.submit();">所有品牌</a>
				@endif
				@foreach($filters['brand'] as $value)
					<a href="#" class="mdc-list-item mdc-list-item__text" role="menuitem" data-target="#filter-brand-{{$value->id}}-checkbox" onclick="
						event.preventDefault();
						var target = document.querySelector(this.dataset.target);
						[].map.call(document.querySelectorAll('input[name=\'' + target.name + '\']'), (el)=>{el.checked=false;});
						target.checked = true;
						target.form.submit();">{{ __($value->name) }}</a>
				@endforeach
			</ul>
		</div>
	</div>
	<div class="my-auto" style="overflow: hidden; white-space: nowrap; text-overflow: ellipsis;">
		@if(old('brand') || old('category'))
		<span>已选:</span>&nbsp;
			@if(old('brand'))
			<span>{{ implode(', ',$filters['brand']->whereIn('id', old('brand'))->pluck('name')->toArray()) }}</span>&nbsp;
			@endif
			@if(old('category'))
			<span>{{ implode(', ', array_map('__', $filters['category']->whereIn('id', old('category'))->pluck('name')->toArray())) }}</span>&nbsp;
			@endif
		@endif
	</div>
</div>
