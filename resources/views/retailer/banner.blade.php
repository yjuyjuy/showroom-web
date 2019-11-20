<div class="m-3 d-flex align-items-end">
	<span class="mdc-typography--headline3">{{ $retailer->name }}</span>
	@auth
		@if(auth()->user()->following_retailers->contains($retailer))
		<button type="button" class="ml-4 mdc-button mdc-button--outlined" onclick="follow('retailer','{{ $retailer->name }}', false)">
			<span class="mdc-button__label">{{ __('following') }}</span>
		</button>
		@else
		<button type="button" class="ml-4 mdc-button mdc-button--outlined" onclick="follow('retailer', '{{ $retailer->name }}')">
			<span class="mdc-button__label">{{ __('follow') }}</span>
		</button>
		@endif
	@endauth

	@guest
	<button type="submit" class="ml-4 mdc-button mdc-button--outlined" form="follow-retailer-form">
		<span class="mdc-button__label">{{ __('follow') }}</span>
	</button>
	<form id="follow-retailer-form" action="{{ route('follow.retailer', ['retailer' => $retailer,]) }}" method="post" style="display: none;"></form>
	@endguest
</div>
<div class="m-3">
	<p class="mdc-typography--headline6">{{ $retailer->description ?? 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.' }}</p>
</div>
