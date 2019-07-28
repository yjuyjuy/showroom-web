<h3 class="mdc-list-group__subheader"
		href="#filter-other-list">
		{{ __('other') }}
</h3>
<ul id="filter-other-list" class="mdc-list filter-list" role="group" aria-label="List with checkbox items">
	<?php $checked = old('show_available_only'); ?>
	<li class="mdc-list-item" aria-checked="{{ $checked ? 'true' : 'false' }}" tabindex="0">
		<span class="mdc-list-item__graphic">
			<div class="mdc-checkbox">
		    <input type="checkbox"
		           class="mdc-checkbox__native-control"
							 value="show_available_only"
							 name="show_available_only"
		           id="show-available-only-checkbox" {{ $checked ? 'checked' : '' }}>
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
	  <label for="show-available-only-checkbox"
					 class="mdc-list-item__text">{{ __('show_available_only') }}</label>
	</li>
	@auth
	<?php $user = auth()->user(); ?>
	@if($user->vendor)
		<?php $checked = old('show_my_stock_only'); ?>
		<li class="mdc-list-item" aria-checked="{{ $checked ? 'true' : 'false' }}" tabindex="0">
			<span class="mdc-list-item__graphic">
				<div class="mdc-checkbox">
					<input type="checkbox"
								 class="mdc-checkbox__native-control"
								 value="show_my_stock_only"
								 name="show_my_stock_only"
								 id="show-not-empty-only-checkbox" {{ $checked ? 'checked' : '' }}>
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
			<label for="show-not-empty-only-checkbox"
						 class="mdc-list-item__text">{{ __('show_my_stock_only') }}</label>
		</li>
	@endif
	@if($user->isSuperAdmin())
		<?php $checked = old('show_empty_only'); ?>
		<li class="mdc-list-item" aria-checked="{{ $checked ? 'true' : 'false' }}" tabindex="0">
			<span class="mdc-list-item__graphic">
				<div class="mdc-checkbox">
			    <input type="checkbox"
			           class="mdc-checkbox__native-control"
								 value="show_empty_only"
								 name="show_empty_only"
			           id="show-empty-only-checkbox" {{ $checked ? 'checked' : '' }}>
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
		  <label for="show-empty-only-checkbox"
						 class="mdc-list-item__text">{{ __('show_empty_only') }}</label>
		</li>
		<?php $checked = old('show_not_empty_only'); ?>
		<li class="mdc-list-item" aria-checked="{{ $checked ? 'true' : 'false' }}" tabindex="0">
			<span class="mdc-list-item__graphic">
				<div class="mdc-checkbox">
			    <input type="checkbox"
			           class="mdc-checkbox__native-control"
								 value="show_not_empty_only"
								 name="show_not_empty_only"
			           id="show-not-empty-only-checkbox" {{ $checked ? 'checked' : '' }}>
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
		  <label for="show-not-empty-only-checkbox"
						 class="mdc-list-item__text">{{ __('show_not_empty_only') }}</label>
		</li>
	</ul>
	<h3 class="mdc-list-group__subheader"
			href="#filter-vendor-list">
			{{ __('vendor') }}
	</h3>
	<ul id="filter-vendor-list" class="mdc-list filter-list" role="group" aria-label="List with checkbox items">
		@foreach(\App\Vendor::all() as $vendor)
		<?php $checked = in_array($vendor->id, (old('vendor') ?? [])); ?>
		<li class="mdc-list-item" aria-checked="{{ $checked ? 'true' : 'false' }}" tabindex="0">
			<span class="mdc-list-item__graphic">
				<div class="mdc-checkbox">
			    <input type="checkbox"
			           class="mdc-checkbox__native-control"
								 value="{{$vendor->id}}"
								 name="vendor[]"
			           id="filter-vendor-{{$vendor->id}}-checkbox" {{ $checked ? 'checked' : '' }}>
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
		  <label for="filter-vendor-{{$vendor->id}}-checkbox"
						 class="mdc-list-item__text">{{ __($vendor->name) }}</label>
		</li>
		@endforeach
	@endif
	@endauth
</ul>

@foreach($filters as $key => $values)
<h3 class="mdc-list-group__subheader"
		href="#filter-{{$key}}-list">
		{{ __($key) }}
</h3>
<ul id="filter-{{$key}}-list" class="mdc-list filter-list" role="group" aria-label="List with checkbox items">
	@foreach($values as $value)
	<?php $checked = in_array($value->id, (old($key) ?? [])); ?>
	<li class="mdc-list-item" aria-checked="{{ $checked ? 'true' : 'false' }}" tabindex="0">
		<span class="mdc-list-item__graphic">
			<div class="mdc-checkbox">
		    <input type="checkbox"
		           class="mdc-checkbox__native-control"
							 value="{{$value->id}}"
							 name="{{ $key }}[]"
		           id="filter-{{$key}}-{{$value->id}}-checkbox" {{ $checked ? 'checked' : '' }}>
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
	  <label for="filter-{{$key}}-{{$value->id}}-checkbox"
					 class="mdc-list-item__text">{{ __($value->name) }}</label>
	</li>
	@endforeach
</ul>
@endforeach
