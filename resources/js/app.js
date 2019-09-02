'use strict'
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
});

[].forEach.call(document.querySelectorAll('.lazy'), (el) => new Image(el.dataset.src));
import {MDCRipple} from '@material/ripple';
import {MDCTopAppBar} from '@material/top-app-bar';
import {MDCDrawer} from "@material/drawer";
import {MDCFormField} from '@material/form-field';
import {MDCCheckbox} from '@material/checkbox';
import {MDCMenu} from '@material/menu';
import {MDCList} from '@material/list';
import {MDCDialog} from '@material/dialog';
import {MDCRadio} from '@material/radio';
import {MDCSelect} from '@material/select';
import {MDCTextFieldHelperText} from '@material/textfield/helper-text';
import {MDCTextField} from '@material/textfield';
import {MDCSelectHelperText} from '@material/select/helper-text';
import {MDCSnackbar} from '@material/snackbar';

// permanent components
const drawer = MDCDrawer.attachTo(document.getElementById('nav-drawer'));
const topAppBarElement = document.getElementById('my-top-app-bar');
const topAppBar = MDCTopAppBar.attachTo(topAppBarElement);
topAppBar.setScrollTarget(window);
topAppBar.listen('MDCTopAppBar:nav', () => {
	drawer.open = !drawer.open;
});

// standard components
[].forEach.call(document.querySelectorAll('.mdc-icon-button'), (el) => {
	const iconButtonRipple = new MDCRipple(el);
	iconButtonRipple.unbounded = true;
});
[].forEach.call(document.querySelectorAll('.mdc-button'), (el) => new MDCRipple(el));
[].forEach.call(document.querySelectorAll('.mdc-fab'), (el) => new MDCRipple(el));
[].forEach.call(document.querySelectorAll('.mdc-radio'), (el) => new MDCRadio(el));
[].forEach.call(document.querySelectorAll('.mdc-checkbox'), (el) => new MDCCheckbox(el));
[].forEach.call(document.querySelectorAll('.mdc-form-field'), (el) => new MDCFormField(el));
[].forEach.call(document.querySelectorAll('.mdc-select'), (el) => {
	const select = new MDCSelect(el);
  select.menu_.quickOpen = true;
	if (!el.classList.contains('optional-form-field')) {
		select.required = true;
	}
	if (el.classList.contains('mdc-select--autosubmit')) {
		select.listen('MDCSelect:change', () => document.getElementById(el.dataset.form).submit());
	}
});
[].forEach.call(document.querySelectorAll('.mdc-menu--with-button'), function(menuElement) {
	const menu = new MDCMenu(menuElement);
	menu.setAnchorCorner(3);
	const button = menuElement.parentElement.querySelector('.open-menu-button');
	button.onclick = () => menu.open = !menu.open;
});
[].forEach.call(document.querySelectorAll('.mdc-text-field-helper-text'), (el) => new MDCTextFieldHelperText(el));
[].forEach.call(document.querySelectorAll('.mdc-select-helper-text'), (el) => new MDCSelectHelperText(el));

if (document.querySelector('.mdc-snackbar')) {
	window.snackbar = new MDCSnackbar(document.querySelector('.mdc-snackbar'));
	snackbar.open();
}
const priceEditorElement = document.querySelector('.price-editor');
[].forEach.call(document.querySelectorAll('.mdc-text-field'), (el) => {
	if (priceEditorElement && priceEditorElement.contains(el)) {
		return;
	}
	const textField = new MDCTextField(el);
	if (!el.classList.contains('optional-form-field')) {
		textField.required = true;
	}
});

// filter/sort options dialog component
if (document.querySelector('.mdc-dialog')) {
  const dialogElement = document.querySelector('.mdc-dialog');
	const dialog = new MDCDialog(dialogElement);
	[].forEach.call(dialogElement.querySelectorAll('.mdc-list'), function(el) {
		el.open = function() {
			this.classList.add('show');
			this.style.height = this.childElementCount * 48 + 'px';
		};
		el.close = function() {
			this.classList.remove('show');
			this.style.height = '0';
		};
		new MDCList(el);
	});

	[].forEach.call(dialogElement.querySelectorAll('.mdc-list-group__subheader'), function(subHeader) {
		subHeader.addEventListener('click', () => {
			event.preventDefault();
			const targetListElement = document.querySelector(subHeader.getAttribute('href'));
			if (targetListElement.classList.contains('show')) {
				targetListElement.close();
			} else {
				[].forEach.call(dialogElement.querySelectorAll('.mdc-list'), (el) => el.close());
				targetListElement.open();
			}
		});
	});
	const sortList = document.getElementById('sort-list');
	sortList.open();
	document.getElementById('display-options-fab').onclick = () => {
		event.preventDefault();
		dialog.open();
	}
}
// product list component
if (document.getElementById('product-datalist')) {
  const productList = document.getElementById('product-datalist');
	var updateThumbnail = function () {
    let card = this.parentElement.parentElement.parentElement;
		let img = card.querySelector('img');
		let selected = productList.querySelector('option[value=\'' + this.value + '\']');
		if (selected) {
      img.src = selected.dataset.imageSrc;
			img.parentElement.href = selected.dataset.href;
		} else {
      img.parentElement.href = "#";
    }
	};
	var linkProduct = function(){
    let card = this.parentElement.parentElement.parentElement;
		let input = card.querySelector('input');
		axios.post('/taobao/link', {'price_id':input.dataset.id,'product_id': input.value})
      .then(response => card.parentElement.removeChild(card))
			.catch(error => window.alert('action failed'));
	};
	var ignoreProduct = function() {
    let card = this.parentElement.parentElement.parentElement;
		let input = card.querySelector('input');
		axios.post('/taobao/ignore', {'price_id':input.dataset.id})
			.then(response => card.parentElement.removeChild(card))
			.catch(error => window.alert('action failed'));
	};
	[].map.call(document.querySelectorAll('.product-card'), function(el) {
    let input = el.querySelector('input');
    input.onchange = updateThumbnail;
		input.ondblclick = () => input.value = '';
		el.querySelector('.ignore-button').onclick = ignoreProduct;
		el.querySelector('.confirm-button').onclick = linkProduct;
	});
}
// admin requests component
if(document.getElementById('admin-requests')){
	var handle = function(route, user_id) {
		axios.post(route, {'user_id': user_id,})
		.then(response=>window.location.reload())
		.catch(error=>window.alert('action failed'))
	};
	[].map.call(document.querySelectorAll('.upgrade-request'), function(el) {
		let user_id = el.dataset.userId;
		el.querySelector('.agree-button').onclick = () => handle('/admin/requests/agree', user_id);
		el.querySelector('.reject-button').onclick = () => handle('/admin/requests/reject', user_id);
	})
}

window.delete_price = (id) => {
	event.preventDefault();
	axios.delete('/prices/' + id)
		.then(response => window.location.replace(response.data.redirect))
		.catch(error => window.alert('action failed'));
};
window.follow_product = (id) => {
	event.preventDefault();
	axios.post('/products/' + id + '/follow')
		.then(response => window.location.reload())
		.catch(error => window.alert('action failed'));
};
window.unfollow_product = (id) => {
	event.preventDefault();
	axios.post('/products/' + id + '/unfollow')
		.then(response => window.location.reload())
		.catch(error => window.alert('action failed'));
};
window.follow_retailer = (name) => {
	event.preventDefault();
	axios.post('/retailer/' + name + '/follow')
		.then(response => window.location.reload())
		.catch(error => window.alert('action failed'));
};
window.unfollow_retailer = (name) => {
	event.preventDefault();
	axios.post('/retailer/' + name + '/unfollow')
		.then(response => window.location.reload())
		.catch(error => window.alert('action failed'));
};
window.unfollow_vendor = (name) => {
	event.preventDefault();
	axios.post('/vendor/' + name + '/unfollow')
		.then(response => window.location = '/following/vendors')
		.catch(error => window.alert('action failed'));
};
window.open_wechat = (name) => {
	event.preventDefault();
	window.alert('打开微信联系卖家' + name);
	window.location = 'weixin://';
};
window.copy_to_clipboard = (text) => {
	navigator.clipboard.writeText(text).catch(()=>window.alert('复制失败'));
};

[].forEach.call(document.querySelectorAll('.lazy'), (el) => el.src = el.dataset.src);