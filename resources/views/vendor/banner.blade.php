<div class="m-3 d-flex align-items-end">
	<span class="mdc-typography--headline3">{{ $vendor->name }}</span>
	<button type="button" class="ml-4 mdc-button mdc-button--outlined" onclick="unfollow_vendor('{{ $vendor->name }}')">
		<span class="mdc-button__label">{{ __('following') }}</span>
	</button>
</div>
<div class="m-3">
	<p class="mdc-typography--headline6">{{ $vendor->description ?? 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.' }}</p>
</div>
