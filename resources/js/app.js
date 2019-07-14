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

import {MDCRipple} from '@material/ripple';
import {MDCTopAppBar} from '@material/top-app-bar';
import {MDCDrawer} from "@material/drawer";
import {MDCFormField} from '@material/form-field';
import {MDCCheckbox} from '@material/checkbox';
import {MDCSwitch} from '@material/switch';
import {MDCMenu} from '@material/menu';
import {MDCList} from '@material/list';
import {MDCDialog} from '@material/dialog';
import {MDCRadio} from '@material/radio';
import {MDCSelect} from '@material/select';
import {MDCTextFieldHelperText} from '@material/textfield/helper-text';
import {MDCTextField} from '@material/textfield';
import {MDCSelectHelperText} from '@material/select/helper-text';

// permanent components
const drawer = MDCDrawer.attachTo(document.getElementById('nav-drawer'));
const topAppBarElement = document.getElementById('my-top-app-bar');
const topAppBar = MDCTopAppBar.attachTo(topAppBarElement);
topAppBar.setScrollTarget(window);
topAppBar.listen('MDCTopAppBar:nav', () => {
	drawer.open = !drawer.open;
});

// standard components
const buttons = [].map.call(document.querySelectorAll('.mdc-button'), function(el) {
	return new MDCRipple(el);
});
const fabs = [].map.call(document.querySelectorAll('.mdc-fab'), function(el) {
	return new MDCRipple(el);
});
const radios = [].map.call(document.querySelectorAll('.mdc-radio'), function(el) {
	return new MDCRadio(el);
});
const checkboxes = [].map.call(document.querySelectorAll('.mdc-checkbox'), function(el) {
	return new MDCCheckbox(el);
});
const formFields = [].map.call(document.querySelectorAll('.mdc-form-field'), function(el) {
	return new MDCFormField(el);
});
const selects = [].map.call(document.querySelectorAll('.mdc-select'), function(el) {
	let select = new MDCSelect(el);
	select.required = true;
	select.menu_.quickOpen = true;
	return select;
});
window.selects = selects;
const textFields = [].map.call(document.querySelectorAll('.mdc-text-field'), function(el) {
	let textField = new MDCTextField(el);
	if(!el.classList.contains('optional-form-field')) {
		textField.required = true;
	}
	return textField;
});
const textFieldHelperTexts = [].map.call(document.querySelectorAll('.mdc-text-field-helper-text'),function(el) {
	return new MDCTextFieldHelperText(el);
});
const selectHelperTexts = [].map.call(document.querySelectorAll('.mdc-select-helper-text'), function(el) {
	return new MDCSelectHelperText(el);
});

// display options dialog component
if (document.querySelector('#display-options-dialog')) {
	const dialogElement = document.getElementById('display-options-dialog');
	const dialog = new MDCDialog(dialogElement);
	const filterListElements = dialogElement.querySelectorAll('.mdc-list');
	const filterLists = [].map.call(filterListElements, function(el) {
		el.open = function() {
			this.classList.add('show');
			this.style.height = this.childElementCount * 48 + 'px';
		};
		el.close = function() {
			this.classList.remove('show');
			this.style.height = '0';
		};
		return new MDCList(el);
	});

	// const filterGroupSubheaders = dialogElement.querySelectorAll('.mdc-list-group__subheader');
	const subHeaders = dialogElement.querySelectorAll('.mdc-list-group__subheader');

	subHeaders.forEach(function(subHeader) {
		subHeader.addEventListener('click', function() {
			event.preventDefault();
			let targetListElement = document.querySelector(this.getAttribute('href'));
			if (targetListElement.classList.contains('show')) {
				targetListElement.close();
			} else {
				filterListElements.forEach((filterListElement) => filterListElement.close());
				targetListElement.open();
			}
		});
	});
	const sortList = document.getElementById('sort-list');
	sortList.classList.add('show');
	sortList.style.height = sortList.childElementCount * 48 + 'px';

	document.getElementById('display-options-fab').onclick = function(event) {
		event.preventDefault();
		console.log('toggle menu');
		dialog.open();
	}
}
