<div class="d-flex w-100 justify-content-center">
	<?php $route_params = Route::current()->parameters(); ?>
		@if($page > 1)
		<a href="{{ route(Route::currentRouteName(), array_merge($route_params, request()->query(), ['page' => $page - 1,])) }}" class="mdc-button">
			<span class="mdc-button__label">上一页</span>
		</a>
		@endif
		@if($page < $total_pages)
		<a href="{{ route(Route::currentRouteName(), array_merge($route_params, request()->query(), ['page' => $page + 1,])) }}" class="mdc-button">
			<span class="mdc-button__label">下一页</span>
		</a>
		@endif
</div>
