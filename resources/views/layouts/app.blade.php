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
		<script src="https://unpkg.com/material-components-web@latest/dist/material-components-web.min.js"></script>
		<script src="{{ asset('js/app.js') }}" defer></script>

		<!-- Fonts -->
		<link rel="dns-prefetch" href="//fonts.gstatic.com">

		<!-- Styles -->
		<link href="{{ asset('css/app.css') }}" rel="stylesheet">

	</head>

	<body>
		<aside id="drawer" class="mdc-drawer mdc-drawer--modal">
			<div class="mdc-drawer__header">
				@guest
				<h3 class="mdc-drawer__title">Welcome</h3>
				<h6 class="mdc-drawer__subtitle">user1234567</h6>
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
					<a class="mdc-list-item mdc-list-item--activated" href="{{route('home')}}" aria-current="page">
						<i class="material-icons mdc-list-item__graphic" aria-hidden="true">shop</i>
						<span class="mdc-list-item__text">Home</span>
					</a>
					<a class="mdc-list-item" href="{{route('home')}}" aria-current="page">
						<i class="material-icons mdc-list-item__graphic" aria-hidden="true">view_list</i>
						<span class="mdc-list-item__text">Wish List</span>
					</a>
					<a class="mdc-list-item" href="{{route('home')}}" aria-current="page">
						<i class="material-icons mdc-list-item__graphic" aria-hidden="true">stars</i>
						<span class="mdc-list-item__text">My collection</span>
					</a>
					<a class="mdc-list-item" href="{{ route('logout') }}"  onclick="event.preventDefault();document.getElementById('logout-form').submit();">
						<i class="material-icons mdc-list-item__graphic" aria-hidden="true">exit_to_app</i>
						<span class="mdc-list-item__text">Logout</span>
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
			<header id="top-app-bar" class="mdc-top-app-bar">
				<div class="mdc-top-app-bar__row">
					<section class="mdc-top-app-bar__section mdc-top-app-bar__section--align-start">
						<a href="#" class="material-icons mdc-top-app-bar__navigation-icon">menu</a>
						<span class="mdc-top-app-bar__title">{{config('app.name','app.name')}}</span>
					</section>
					<section class="mdc-top-app-bar__section mdc-top-app-bar__section--align-end" role="toolbar">
			      <a href="#" class="material-icons mdc-top-app-bar__action-item" aria-label="Download">file_download</a>
			      <a href="#" class="material-icons mdc-top-app-bar__action-item" aria-label="Print this page">print</a>
			      <a href="#" class="material-icons mdc-top-app-bar__action-item" aria-label="Bookmark this page">bookmark</a>
			    </section>
				</div>
			</header>
			<main class="mdc-top-app-bar--short-fixed-adjust">
				<div class="pt-4">
					@yield('content')
				</div>
			</main>
		</div>
	</body>

</html>
