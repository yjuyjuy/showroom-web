
<h3 id="sort-list-group__subheader"
		class="mdc-list-group__subheader"
		href="#sort-list">
		{{ __('sort') }}
</h3>
<ul id="sort-list" class="mdc-list" role="radiogroup">
	@foreach($sortOptions as $option)
	<?php $checked = (old('sort') === $option || (!old('sort') && $option == 'default')); ?>
	<li class="mdc-list-item" role="radio" aria-checked="{{ $checked ? 'true' : 'false' }}" tabindex="0">
		<span class="mdc-list-item__graphic">
			<div class="mdc-radio">
		    <input class="mdc-radio__native-control"
							 type="radio"
							 id="sort-{{$option}}-radio"
							 name="sort"
							 value="{{$option}}" {{ $checked ? 'checked' : '' }}>
		    <div class="mdc-radio__background">
		      <div class="mdc-radio__outer-circle"></div>
		      <div class="mdc-radio__inner-circle"></div>
		    </div>
		  </div>
		</span>
	  <label for="sort-{{$option}}-radio"
					 class="mdc-list-item__text">{{ __($option) }}</label>
	</li>
	@endforeach
</ul>
