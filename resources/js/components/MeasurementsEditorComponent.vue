<template>
<div class="data-editor">
	<div class="d-flex data-editor__row">
		<div class="mdc-text-field mdc-text-field--outlined mdc-text-field--no-label">
			<input type="text" class="mdc-text-field__input" aria-label="Label" disabled>
			<div class="mdc-notched-outline">
				<div class="mdc-notched-outline__leading"></div>
				<div class="mdc-notched-outline__trailing"></div>
			</div>
		</div>
		<div v-for="(field, index) in fields" class="mdc-text-field mdc-text-field--outlined mdc-text-field--no-label">
			<input type="text" class="mdc-text-field__input" aria-label="Label" v-model="fields[index]" @input="check_empty" @change="update" placeholder="+项目">
			<div class="mdc-notched-outline">
				<div class="mdc-notched-outline__leading"></div>
				<div class="mdc-notched-outline__trailing"></div>
			</div>
		</div>
	</div>

	<div v-for="(size, row) in sizes" class="d-flex data-editor__row">
		<div class="mdc-text-field mdc-text-field--outlined mdc-text-field--no-label">
			<input type="text" class="mdc-text-field__input" aria-label="Label" spellcheck="false" v-model="sizes[row]" @input="check_empty" @change="update" placeholder="+尺码">
			<div class="mdc-notched-outline">
				<div class="mdc-notched-outline__leading"></div>
				<div class="mdc-notched-outline__trailing"></div>
			</div>
		</div>
		<div v-for="(field, col) in fields" class="mdc-text-field mdc-text-field--outlined mdc-text-field--no-label">
			<input type="text" class="mdc-text-field__input" aria-label="Label" v-model="data[row][col]" spellcheck="false" @input="check_empty" @change="update">
			<div class="mdc-notched-outline">
				<div class="mdc-notched-outline__leading"></div>
				<div class="mdc-notched-outline__trailing"></div>
			</div>
		</div>
	</div>
	<input type="hidden" name="data" :value="json_data">
</div>
</template>

<script>
import {MDCTextField} from '@material/textfield';

export default {
	props: {
		input: Object,
	},
	data: function() {
		return {
			data: this.input,
			fields: [],
			sizes: [],
		};
	},
	mounted() {
		let data = [];
		for (let field in this.data) {
			if (!this.fields.includes(field))
				this.fields.push(field);
			for (let size in this.data[field]) {
				if (!this.sizes.includes(size))
					this.sizes.push(size);
			}
		}
		const SML = ['XXS','XS','S','M','L','XL','XXL','XXXL'];
		this.sizes.sort(function(a, b) {
			if (SML.includes(a)) {
				a = SML.indexOf(a);
			}
			if (SML.includes(b)) {
				b = SML.indexOf(b);
			}
			return a - b;
		})
		for (let row in this.sizes) {
			data[row] = [];
			for (let col in this.fields) {
				data[row][col] = this.data[this.fields[col]][this.sizes[row]];
			}
		}
		data.push([]);
		this.data = data;
		this.fields.push('');
		this.sizes.push('');
	},
	computed: {
		json_data: function() {
			let data = {}
			for (let col in this.fields) {
				if (this.fields[col] === '') continue;
				data[this.fields[col]] = {};
				for (let row in this.sizes) {
					if (this.sizes[row] === '') continue;
					if (this.data[row][col])
						data[this.fields[col]][this.sizes[row]] = this.data[row][col].toLowerCase();
				}
			}
			return JSON.stringify(data);
		}
	},
	watch: {},
	methods: {
		update: function() {
			this.clear_empty();
			this.check_empty();
		},
		check_empty: function() {
			if (this.sizes[this.sizes.length-1]) {
				this.sizes.push("");
			}
			if (this.fields[this.fields.length-1]) {
				this.fields.push("");
			}
			while (this.data.length < this.sizes.length) {
				this.data.push([]);
			}
		},
		clear_empty: function() {
			for (let index in this.sizes) {
				if (!this.sizes[index] && this.data[index].every((el) => !el)) {
					this.sizes.splice(index,1);
					this.data.splice(index,1);
				}
			}
			for (let index in this.fields) {
				if (!this.fields[index] && this.data.every((row) => !row[index])) {
					this.fields.splice(index,1);
					this.data.forEach((row) => row.splice(index,1));
				}
			}
		}
	},
	updated: function() {
		this.$nextTick(function() {
			this.$el.querySelectorAll('.mdc-text-field').forEach(function(el) {
				if (el.dataset.attached !== true) {
					const textField = new MDCTextField(el);
					el.dataset.attached = true
				}
			});
		});
	}

}
</script>
