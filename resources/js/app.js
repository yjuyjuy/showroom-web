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
const buttonRipples = [].map.call(document.querySelectorAll('.mdc-button'), function(el) {
	return new MDCRipple(el);
});
const iconButtonRipples = [].map.call(document.querySelectorAll('.mdc-icon-button'), function(el) {
	let iconButtonRipple = new MDCRipple(el);
	iconButtonRipple.unbounded = true;
	return iconButtonRipple;
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
	if (!el.classList.contains('optional-form-field')) {
		select.required = true;
	}
	if (el.classList.contains('mdc-select--autosubmit')) {
		select.listen('MDCSelect:change', function() {
			document.getElementById(el.dataset.form).submit();
		});
	}
	select.menu_.quickOpen = true;
	return select;
});
[].map.call(document.querySelectorAll('.mdc-menu--with-button'), function(menuElement) {
	const menu = new MDCMenu(menuElement);
	window.menu = menu;
	menu.setAnchorCorner(3);
	const button = menuElement.parentElement.querySelector('.open-menu-button');
	button.onclick = function(event) {
		event.preventDefault();
		menu.open = !menu.open;
	}
});
let el = document.querySelector('.mdc-snackbar');
if (el) {
	const snackbar = new MDCSnackbar(el);
	snackbar.open();
	window.snackbar = snackbar;
}
const priceEditorElement = document.querySelector('.price-editor');
const textFields = [].map.call(document.querySelectorAll('.mdc-text-field'), function(el) {
	if (priceEditorElement && priceEditorElement.contains(el)) {
		return;
	}
	let textField = new MDCTextField(el);
	if (!el.classList.contains('optional-form-field')) {
		textField.required = true;
	}
	return textField;
});
const textFieldHelperTexts = [].map.call(document.querySelectorAll('.mdc-text-field-helper-text'), function(el) {
	return new MDCTextFieldHelperText(el);
});
const selectHelperTexts = [].map.call(document.querySelectorAll('.mdc-select-helper-text'), function(el) {
	return new MDCSelectHelperText(el);
});
const options = {
	month: 'short',
	day: '2-digit',
	hour: '2-digit',
	minute: '2-digit',
	hour12: false,
};
[].map.call(document.querySelectorAll('.timestamp'), function(el) {
	let t = new Date(parseFloat(el.dataset.timestamp));
	el.textContent = new Intl.DateTimeFormat('zh', options).format(t);
});


// filter/sort options dialog component
const dialogElement = document.getElementById('display-options-dialog');
if (dialogElement) {
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
		dialog.open();
	}
}
// product list component
var productList = document.getElementById('product-datalist');
if (productList) {
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

window.delete_price = function(id) {
	event.preventDefault();
	axios.delete('/prices/' + id)
		.then(response => window.location.replace(response.data.redirect))
		.catch(error => window.alert('action failed'));
}
window.follow_product = function(id) {
	event.preventDefault();
	axios.post('/products/' + id + '/follow')
		.then(response => window.location.reload())
		.catch(error => window.alert('action failed'));
}
window.unfollow_product = function(id) {
	event.preventDefault();
	axios.post('/products/' + id + '/unfollow')
		.then(response => window.location.reload())
		.catch(error => window.alert('action failed'));
}
window.follow_retailer = function(name) {
	event.preventDefault();
	axios.post('/retailer/' + name + '/follow')
		.then(response => window.location.reload())
		.catch(error => window.alert('action failed'));
}
window.unfollow_retailer = function(name) {
	event.preventDefault();
	axios.post('/retailer/' + name + '/unfollow')
		.then(response => window.location.reload())
		.catch(error => window.alert('action failed'));
}
window.unfollow_vendor = function(name) {
	event.preventDefault();
	axios.post('/vendor/' + name + '/unfollow')
		.then(response => window.location = '/following/vendors')
		.catch(error => window.alert('action failed'));
}
window.open_wechat = function(name) {
	event.preventDefault();
	window.alert('打开微信联系卖家' + name);
	window.location = 'weixin://';
}
window.copy_to_clipboard = function(text) {
	navigator.clipboard.writeText(text).catch(()=>window.alert('复制失败'));
}
