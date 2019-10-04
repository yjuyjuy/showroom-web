@foreach($filters as $key => $values)
<h3 class="mdc-list-group__subheader"
		href="#filter-{{$key}}-list">
		{{ __($key) }}
</h3>
<ul id="filter-{{$key}}-list" class="mdc-list filter-list" role="group" aria-label="List with checkbox items">
	@foreach($values as $token => $value)
	<?php $checked = in_array($token, (old($key) ?? [])); ?>
	<li class="mdc-list-item" aria-checked="{{ $checked ? 'true' : 'false' }}" tabindex="0">
		<span class="mdc-list-item__graphic">
			<div class="mdc-checkbox">
		    <input type="checkbox"
		           class="mdc-checkbox__native-control"
							 value="{{$token}}"
							 name="{{ $key }}[]"
		           id="filter-{{$key}}-{{$token}}-checkbox" {{ $checked ? 'checked' : '' }}>
		    <div class="mdc-checkbox__background">
		      <svg class="mdc-checkbox__checkmark"
		           viewBox="0 0 24 24">
		        <path class="mdc-checkbox__checkmark-path"
		              fill="none"
		              d="M1.73,12.91 8.1,19.28 22.79,4.59"/>
		      </svg>
		      <div class="mdc-checkbox__mixedmark"></div>
		    </div>
		  </div>
		</span>
	  <label for="filter-{{$key}}-{{$token}}-checkbox"
					 class="mdc-list-item__text">{{ __($value) }}</label>
	</li>
	@endforeach
</ul>
@endforeach
