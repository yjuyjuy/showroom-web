<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<!-- Material -->
		<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">

		<!-- CSRF Token -->
		<meta name="csrf-token" content="{{ csrf_token() }}">

		<title>@yield('title','TheShowroom')</title>

		<!-- Scripts -->
		<script src="{{ asset('js/app.js') }}" defer></script>

		<!-- Fonts -->
		<link rel="dns-prefetch" href="//fonts.gstatic.com">

		<!-- Styles -->
		<link href="{{ asset('css/app.css') }}" rel="stylesheet">

	</head>

	<body>
		<aside id="nav-drawer" class="mdc-drawer mdc-drawer--modal">
			<div class="mdc-drawer__header">
				@guest
				<h3 class="mdc-drawer__title">{{ __('Welcome') }}</h3>
				<h6 class="mdc-drawer__subtitle">{{ __('guest') }}</h6>
				@else
				<h3 class="mdc-drawer__title">{{ auth()->user()->username }}</h3>
				<h6 class="mdc-drawer__subtitle">{{ auth()->user()->email }}</h6>
				@endguest
			</div>
			<div class="mdc-drawer__content">
				<nav class="mdc-list">
					@guest
					<a class="mdc-list-item mdc-list-item--activated" href="{{ route('login') }}">
						<i class="material-icons mdc-list-item__graphic" aria-hidden="true">person</i>
						<span class="mdc-list-item__text">{{ __('Login') }}</span>
					</a>
					<a class="mdc-list-item" href="{{ route('register') }}">
						<i class="material-icons mdc-list-item__graphic" aria-hidden="true">person_add</i>
						<span class="mdc-list-item__text">{{ __('Register') }}</span>
					</a>
					@else
					<a class="mdc-list-item mdc-list-item--activated" href="{{route('home')}}">
						<i class="material-icons mdc-list-item__graphic" aria-hidden="true">home</i>
						<span class="mdc-list-item__text">{{ __('Home') }}</span>
					</a>
					<a class="mdc-list-item" href="{{route('home')}}">
						<i class="material-icons mdc-list-item__graphic" aria-hidden="true">view_list</i>
						<span class="mdc-list-item__text">{{ __('Wish list') }}</span>
					</a>
					<a class="mdc-list-item" href="{{route('home')}}">
						<i class="material-icons mdc-list-item__graphic" aria-hidden="true">stars</i>
						<span class="mdc-list-item__text">{{ __('My collection') }}</span>
					</a>
					@if(auth()->user()->vendor)
					<a class="mdc-list-item" href="{{ route('prices.index') }}">
						<i class="material-icons mdc-list-item__graphic" aria-hidden="true">dashboard</i>
						<span class="mdc-list-item__text">{{ __('Price sheet') }}</span>
					</a>
					@endif
					@if(auth()->user()->isSuperAdmin())
					<a class="mdc-list-item" href="{{ route('products.create') }}">
						<i class="material-icons mdc-list-item__graphic" aria-hidden="true">add_box</i>
						<span class="mdc-list-item__text">{{ __('Create product') }}</span>
					</a>
					<a class="mdc-list-item" href="{{ route('logs') }}">
						<i class="material-icons mdc-list-item__graphic" aria-hidden="true">ballot</i>
						<span class="mdc-list-item__text">{{ __('Change log') }}</span>
					</a>
					@endif
					<a class="mdc-list-item" href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
						<i class="material-icons mdc-list-item__graphic" aria-hidden="true">exit_to_app</i>
						<span class="mdc-list-item__text">{{ __('Logout') }}</span>
					</a>
					<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
						@csrf
					</form>
					@endguest
				</nav>
			</div>
		</aside>
		<div class="mdc-drawer-scrim"></div>

		<div id="app">
			<header id="my-top-app-bar" class="mdc-top-app-bar mdc-top-app-bar--fixed">
				<div class="mdc-top-app-bar__row">
					<section class="mdc-top-app-bar__section mdc-top-app-bar__section--align-start">
						<a href="#" class="material-icons mdc-top-app-bar__navigation-icon">menu</a>
						<a href="/" class="mdc-top-app-bar__title">{{config('app.name','app.name')}}</a>
					</section>
					<section class="mdc-top-app-bar__section mdc-top-app-bar__section--align-end" role="toolbar">
						<a href="{{ route('products.index',['sort' => 'random', 'category' => old('category'),'brand' => old('brand'),'season' => old('season'),'color' => old('color')]) }}"
							 class="material-icons mdc-top-app-bar__action-item" aria-label="surprise">casino</a>
					</section>
				</div>
			</header>
			<main id="main-content" class="main-content mdc-top-app-bar--fixed-adjust">
				<div class="py-2"></div>
				@yield('content')
				<div class="py-2"></div>
			</main>
		</div>
	</body>

</html>
