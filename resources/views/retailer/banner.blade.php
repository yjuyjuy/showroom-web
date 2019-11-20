<div class="m-3 d-flex align-items-end">
	<span class="mdc-typography--headline3">{{ $retailer->name }}</span>
	@if(auth()->user() && auth()->user()->following_retailers->contains($retailer))
	<button type="button" class="ml-4 mdc-button mdc-button--outlined" onclick="follow('retailer','{{ $retailer->name }}', false)">
		<span class="mdc-button__label">{{ __('following') }}</span>
	</button>
	@elseif(auth()->user())
	<button type="button" class="ml-4 mdc-button mdc-button--outlined" onclick="follow('retailer', '{{ $retailer->name }}')">
		<span class="mdc-button__label">{{ __('follow') }}</span>
	</button>
	@else
	<a href="{{ route('follow.retailer', ['retailer' => $retailer,]) }}" class="ml-4 mdc-button mdc-button--outlined">
		<span class="mdc-button__label">{{ __('follow') }}</span>
	</a>
	@endif
</div>
<div class="m-3">
	<p class="mdc-typography--headline6">{{ $retailer->description ?? 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.' }}</p>
</div>
