<div class="mdc-select">
	<input type="hidden" name="brand">
	<i class="mdc-select__dropdown-icon"></i>
	<div class="mdc-select__selected-text"></div>
	<div class="mdc-select__menu mdc-menu mdc-menu-surface">
		<ul class="mdc-list">
			@foreach(\App\Brand::all() as $brand)
			<?php $selected = old('brand', optional($product)->brand->id) === $brand->id; ?>
			@if($selected)
			<li data-value="{{ $brand->id }}" class="mdc-list-item mdc-list-item--selected" aria-selected="true">
				@else
			<li data-value="{{ $brand->id }}" class="mdc-list-item">
				@endif
				{{ $brand->name }}
			</li>
			@endforeach
		</ul>
	</div>
	<span class="mdc-floating-label">{{ __('brand') }}</span>
	<div class="mdc-line-ripple"></div>
</div>
@error('brand')
<p class="mdc-select-helper-text mdc-select-helper-text--persistent" role="alert">{{ $message }}</p>
@enderror


<div class="mdc-select">
	<input type="hidden" name="season">
	<i class="mdc-select__dropdown-icon"></i>
	<div class="mdc-select__selected-text"></div>
	<div class="mdc-select__menu mdc-menu mdc-menu-surface">
		<ul class="mdc-list">
			@foreach(\App\Season::all() as $season)
			<?php $selected = old('season', optional($product)->season->id) === $season->id; ?>
			@if($selected)
			<li data-value="{{ $season->id }}" class="mdc-list-item mdc-list-item--selected" aria-selected="true">
				@else
			<li data-value="{{ $season->id }}" class="mdc-list-item">
				@endif
				{{ $season->name }}
			</li>
			@endforeach
		</ul>
	</div>
	<span class="mdc-floating-label">{{ __('season') }}</span>
	<div class="mdc-line-ripple"></div>
</div>
@error('season')
<p class="mdc-select-helper-text mdc-select-helper-text--persistent" role="alert">{{ $message }}</p>
@enderror

<div class="mdc-text-field">
	<input type="text" class="mdc-text-field__input" name="name_cn" autocomplete="off" value="{{ old('name_cn') ?? ( $product->name_cn ?? '' ) }}">
	<label class="mdc-floating-label" for="my-text-field">{{ __('product name_cn') }}</label>
	<div class="mdc-line-ripple"></div>
</div>

@error('name_cn')
<div class="mdc-text-field-helper-line">
	<div class="mdc-text-field-helper-text mdc-text-field-helper-text--persistent">{{ $message }}</div>
</div>
@enderror

<div class="mdc-text-field">
	<input type="text" class="mdc-text-field__input" name="name" autocomplete="off" value="{{ old('name') ?? ( $product->name ?? '' ) }}">
	<label class="mdc-floating-label" for="my-text-field">{{ __('product name') }}</label>
	<div class="mdc-line-ripple"></div>
</div>
@error('name')
<div class="mdc-text-field-helper-line">
	<div class="mdc-text-field-helper-text mdc-text-field-helper-text--persistent">{{ $message }}</div>
</div>
@enderror

<div class="mdc-select">
	<input type="hidden" name="category">
	<i class="mdc-select__dropdown-icon"></i>
	<div class="mdc-select__selected-text"></div>
	<div class="mdc-select__menu mdc-menu mdc-menu-surface">
		<ul class="mdc-list">
			@foreach(\App\Category::all() as $category)
			<?php $selected = old('category', optional($product)->category->id) === $category->id; ?>
			@if($selected)
			<li data-value="{{ $category->id }}" class="mdc-list-item mdc-list-item--selected" aria-selected="true">
				@else
			<li data-value="{{ $category->id }}" class="mdc-list-item">
				@endif
				{{ $category->name }}
			</li>
			@endforeach
		</ul>
	</div>
	<span class="mdc-floating-label">{{ __('category') }}</span>
	<div class="mdc-line-ripple"></div>
</div>
@error('category')
<p class="mdc-select-helper-text mdc-select-helper-text--persistent" role="alert">{{ $message }}</p>
@enderror

<div class="mdc-select">
	<input type="hidden" name="color">
	<i class="mdc-select__dropdown-icon"></i>
	<div class="mdc-select__selected-text"></div>
	<div class="mdc-select__menu mdc-menu mdc-menu-surface">
		<ul class="mdc-list">
			@foreach(\App\Color::all() as $color)
			<?php $selected = old('color', optional($product)->color->id) === $color->id; ?>
			@if($selected)
			<li data-value="{{ $color->id }}" class="mdc-list-item mdc-list-item--selected" aria-selected="true">
				@else
			<li data-value="{{ $color->id }}" class="mdc-list-item">
				@endif
				{{ $color->name }}
			</li>
			@endforeach
		</ul>
	</div>
	<span class="mdc-floating-label">{{ __('color') }}</span>
	<div class="mdc-line-ripple"></div>
</div>
@error('color')
<p class="mdc-select-helper-text mdc-select-helper-text--persistent" role="alert">{{ $message }}</p>
@enderror

<div class="mdc-text-field mdc-text-field--textarea optional-form-field">
	<textarea class="mdc-text-field__input" name="comment" rows="2">{{ old('comment') ?? ( $product->comment ?? '' ) }}</textarea>
	<div class="mdc-notched-outline">
		<div class="mdc-notched-outline__leading"></div>
		<div class="mdc-notched-outline__notch">
			<label for="textarea" class="mdc-floating-label">{{ __('comment') }}</label>
		</div>
		<div class="mdc-notched-outline__trailing"></div>
	</div>
</div>
@error('comment')
<div class="mdc-text-field-helper-line">
	<div class="mdc-text-field-helper-text mdc-text-field-helper-text--persistent">{{ $message }}</div>
</div>
@enderror
