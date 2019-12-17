<div class="d-flex">
	@foreach($filters as $key => $values)
	<div class="mdc-menu-surface--anchor">
		<button type="button" class="mdc-button open-menu-button">
			<span class='mdc-button__label'>
				@if(old($key)&&sizeof(old($key)) == 1)
					{{ __($values->firstWhere('id', old($key)[0])->name) }}
				@else
					{{ __($key) }}
				@endif
			</span>
		</button>
		<div class="mdc-menu mdc-menu-surface mdc-menu--with-button">
			<ul class="mdc-list" role="menu" aria-hidden="true" aria-orientation="vertical" tabindex="-1">
				@if(old($key))
				<a href="#" class="mdc-list-item mdc-list-item__text" role="menuitem" data-target="input[name='{{$key}}[]']" onclick="
					event.preventDefault();
					var target = document.querySelector(this.dataset.target);
					[].map.call(document.querySelectorAll('input[name=\'' + target.name + '\']'), (el)=>{el.checked=false;});
					target.form.submit();">{{ __('All').__($key) }}</a>
				@endif
				@foreach($values as $value)
					<a href="#" class="mdc-list-item mdc-list-item__text" role="menuitem" data-target="#filter-{{$key}}-{{$value->id}}-checkbox" onclick="
						event.preventDefault();
						var target = document.querySelector(this.dataset.target);
						[].map.call(document.querySelectorAll('input[name=\'' + target.name + '\']'), (el)=>{el.checked=false;});
						target.checked = true;
						target.form.submit();">{{ __($value->name) }}</a>
				@endforeach
			</ul>
		</div>
	</div>
	@endforeach
</div>
