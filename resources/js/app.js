/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('prices-editor', require('./components/PricesEditorComponent.vue').default);
Vue.component('images-slider', require('./components/ImagesSliderComponent.vue').default);
Vue.component('image-item', require('./components/ImageItemComponent.vue').default);
Vue.component('empty-image', require('./components/EmptyImageComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
	el: '#app',
	methods: {
		deletePrice: function(id) {
			axios.delete('/prices/' + id)
				.then(response => window.location.replace(response.data.redirect))
				.catch(error => console.log(error));
		}
	},
});

window.addEventListener('load', function() {
	const buttons = [].map.call(document.querySelectorAll('.mdc-button'), function(el){
		return mdc.ripple.MDCRipple.attachTo(el);
	});
	const topAppBarElement = document.getElementById('top-app-bar');
	const topAppBar = mdc.topAppBar.MDCTopAppBar.attachTo(topAppBarElement);

	const drawer = mdc.drawer.MDCDrawer.attachTo(document.getElementById('drawer'));
	topAppBar.listen('MDCTopAppBar:nav', () => {
		drawer.open = !drawer.open;
	});
});
