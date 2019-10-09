<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link href="{{ asset('css/fonts.css') }}" rel="stylesheet">
		<!-- CSRF Token -->
		<meta name="csrf-token" content="{{ csrf_token() }}">

		<title>@yield('title','') - {{ env('APP_NAME', 'TheShowroom') }}</title>

		<!-- Scripts -->
		<script src="{{ asset('js/app.js') }}" defer></script>

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
					<?php $user = auth()->user(); ?>
					<a class="mdc-list-item mdc-list-item--activated" href="{{ route('products.index') }}">
						<i class="material-icons mdc-list-item__graphic" aria-hidden="true">home</i>
						<span class="mdc-list-item__text">{{ __('All products') }}</span>
					</a>
					<a class="mdc-list-item" href="{{ route('products.random') }}">
						<i class="material-icons mdc-list-item__graphic" aria-hidden="true">casino</i>
						<span class="mdc-list-item__text">{{ __('Random product') }}</span>
					</a>
					<a class="mdc-list-item" href="{{ route('following.products') }}">
						<i class="material-icons mdc-list-item__graphic" aria-hidden="true">stars</i>
						<span class="mdc-list-item__text">{{ __('Following products') }}</span>
					</a>
					<a class="mdc-list-item" href="{{ route('following.retailers') }}">
						<i class="material-icons mdc-list-item__graphic" aria-hidden="true">store</i>
						<span class="mdc-list-item__text">{{ __('Following retailers') }}</span>
					</a>
					<hr class="mdc-list-divider">
					<h6 class="mdc-list-group__subheader">高级功能</h6>
					@if($user->is_reseller)
					<a class="mdc-list-item" href="{{ route('reseller.products.index') }}">
						<i class="material-icons mdc-list-item__graphic" aria-hidden="true">work_outline</i>
						<span class="mdc-list-item__text">所有报价</span>
					</a>
					<a class="mdc-list-item" href="{{ route('following.vendors') }}">
						<i class="material-icons mdc-list-item__graphic" aria-hidden="true">track_changes</i>
						<span class="mdc-list-item__text">{{ __('Following vendors') }}</span>
					</a>
					@elseif(!$user->is_rejected)
					<a class="mdc-list-item" href="{{ route('account.status') }}">
						<i class="material-icons mdc-list-item__graphic" aria-hidden="true">verified_user</i>
						<span class="mdc-list-item__text">申请卖家账户</span>
					</a>
					@endif
					@if($user->vendor)
					<a class="mdc-list-item" href="{{ route('prices.index') }}">
						<i class="material-icons mdc-list-item__graphic" aria-hidden="true">list_alt</i>
						<span class="mdc-list-item__text">{{ __('My price sheet') }}</span>
					</a>
					@endif
					@if($user->isSuperAdmin())
					<hr class="mdc-list-divider">
					<h6 class="mdc-list-group__subheader">管理员功能</h6>
					<a class="mdc-list-item" href="{{ route('products.create') }}">
						<i class="material-icons mdc-list-item__graphic" aria-hidden="true">add_box</i>
						<span class="mdc-list-item__text">{{ __('Create product') }}</span>
					</a>
					<a class="mdc-list-item" href="{{ route('admin.index') }}">
						<i class="material-icons mdc-list-item__graphic" aria-hidden="true">extension</i>
						<span class="mdc-list-item__text">手动执行程序</span>
					</a>
					<a class="mdc-list-item" href="{{ route('admin.taobao.index') }}">
						<i class="material-icons mdc-list-item__graphic" aria-hidden="true">dashboard</i>
						<span class="mdc-list-item__text">淘宝管理</span>
					</a>
					<a class="mdc-list-item" href="{{ route('websites.index') }}">
						<i class="material-icons mdc-list-item__graphic" aria-hidden="true">store</i>
						<span class="mdc-list-item__text">所有网站</span>
					</a>
					<hr class="mdc-list-divider">
					@endif
					<a class="mdc-list-item" href="{{ route('suggestion.create') }}">
						<i class="material-icons mdc-list-item__graphic" aria-hidden="true">add_comment</i>
						<span class="mdc-list-item__text">功能建议</span>
					</a>
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
						<a href="/" class="mdc-top-app-bar__title">{{config('app.name','app.name')}}@yield('header','')</a>
					</section>
					<section class="mdc-top-app-bar__section mdc-top-app-bar__section--align-end" role="toolbar">
						<a href="{{ route('products.random') }}"
							 class="material-icons mdc-top-app-bar__action-item" aria-label="surprise">casino</a>
					</section>
				</div>
			</header>
			<main class="main-content mdc-top-app-bar--fixed-adjust">
				<div class="py-2"></div>
				@yield('content')
				<div class="py-2"></div>
			</main>
			<footer style="height:72px;"></footer>
		</div>
	</body>

</html>
