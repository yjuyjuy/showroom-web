require('./bootstrap');
window.Vue = require('vue');

Vue.component('prices-editor', require('./components/PricesEditorComponent.vue').default);
Vue.component('carousel', require('./components/CarouselComponent.vue').default);
Vue.component('image-item', require('./components/ImageItemComponent.vue').default);
Vue.component('empty-image', require('./components/EmptyImageComponent.vue').default);
const app = new Vue({
	el: '#app',
});

import {MDCTopAppBar} from '@material/top-app-bar';
import {MDCDrawer} from "@material/drawer";
import {MDCMenu} from '@material/menu';
import {MDCList} from '@material/list';
import {MDCDialog} from '@material/dialog';
import {MDCTextFieldHelperText} from '@material/textfield/helper-text';
import {MDCTextField} from '@material/textfield';
import {MDCSelect} from '@material/select';
import {MDCSelectHelperText} from '@material/select/helper-text';
import {MDCSnackbar} from '@material/snackbar';

// mdc components
const drawer = MDCDrawer.attachTo(document.getElementById('nav-drawer'));
const topAppBarElement = document.getElementById('my-top-app-bar');
const topAppBar = MDCTopAppBar.attachTo(topAppBarElement);
topAppBar.setScrollTarget(window);
topAppBar.listen('MDCTopAppBar:nav', () => {
	drawer.open = !drawer.open;
});
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
if (!document.querySelector('.price-editor')) {
  [].forEach.call(document.querySelectorAll('.mdc-text-field'), (el) => {
    const textField = new MDCTextField(el);
    if (!el.classList.contains('optional-form-field')) {
      textField.required = true;
    }
  });
}
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
if (document.querySelector('img.lazy')) {
	[].map.call(document.querySelectorAll('img.lazy'), function(el) {
		el.src = '/storage/icons/ImagePlaceholder.svg';
		if (el.dataset.src) {
			let image = new Image();
			image.src = el.dataset.src;
			image.onload = () => el.src = el.dataset.src;
		}
	});
}
// axios functions
window.delete_price = (id) => {
	axios.delete('/prices/' + id)
		.then(response => window.location.replace(response.data.redirect))
		.catch(error => window.alert('action failed'));
};
window.follow = (model, id, follow = true) => {
  axios.post('/' + model + '/' + id + ((follow) ? '/follow' : '/unfollow'))
    .then(response => {
      if (response.data.redirect) {
        window.location = response.data.redirect;
      } else {
        window.location.reload();
      }
		})
    .catch(error => window.alert('action failed'));
};
window.open_wechat = (name) => {
	event.preventDefault();
	window.alert('打开微信联系卖家' + name);
	window.location = 'weixin://';
};
window.axios_submit = function(button) {
	var formData = new FormData();
	for(let el of button.form.elements) {
		if (el.name) {
			if (el.files) {
				for (let file of el.files) {
					formData.append(el.name + '[]', file)
				}
			} else {
				formData.append(el.name, el.value);
			}
		}
	}
	axios.post(button.form.action, formData).then(response=>window.location.reload()).catch(error=>button.form.submit());
};
