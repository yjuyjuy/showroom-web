<template>
<div class="data-editor">
	<div class="d-flex data-editor__row mb-2">
		<div class="mdc-text-field mdc-text-field--outlined mdc-text-field--no-label">
			<input type="text" class="mdc-text-field__input" aria-label="Label" disabled>
			<div class="mdc-notched-outline">
				<div class="mdc-notched-outline__leading"></div>
				<div class="mdc-notched-outline__trailing"></div>
			</div>
		</div>
		<div class="mdc-text-field mdc-text-field--outlined mdc-text-field--no-label">
			<input type="text" class="mdc-text-field__input" aria-label="Label" v-model="fill_offer" placeholder="调货价">
			<div class="mdc-notched-outline">
				<div class="mdc-notched-outline__leading"></div>
				<div class="mdc-notched-outline__trailing"></div>
			</div>
		</div>
		<div class="mdc-text-field mdc-text-field--outlined mdc-text-field--no-label">
			<input type="text" class="mdc-text-field__input" aria-label="Label" v-model="fill_retail" :placeholder="computed_retail(fill_offer) || '零售价'">
			<div class="mdc-notched-outline">
				<div class="mdc-notched-outline__leading"></div>
				<div class="mdc-notched-outline__trailing"></div>
			</div>
		</div>
		<div class="mdc-text-field mdc-text-field--outlined mdc-text-field--no-label flex-shrink-1">
			<input type="text" class="mdc-text-field__input" aria-label="Label" v-model="fill_stock" placeholder="库存">
			<div class="mdc-notched-outline">
				<div class="mdc-notched-outline__leading"></div>
				<div class="mdc-notched-outline__trailing"></div>
			</div>
		</div>
		<div class="flex-shrink-1">
			<button @click.prevent="fill" type="button" class="mdc-button mdc-button--unelevated" tabindex="-1">
				<span class="mdc-button__label">填充</span>
			</button>
		</div>
	</div>
	<div v-for="(price,index) in prices" class="d-flex data-editor__row">
		<div class="mdc-text-field mdc-text-field--outlined mdc-text-field--no-label">
			<input type="text" class="mdc-text-field__input" aria-label="Label" @input="check_empty" @change="update(index)" v-model="price.size" :autofocus="index == 0" spellcheck="false" placeholder="+尺码">
			<div class="mdc-notched-outline">
				<div class="mdc-notched-outline__leading"></div>
				<div class="mdc-notched-outline__trailing"></div>
			</div>
		</div>
		<div class="mdc-text-field mdc-text-field--outlined mdc-text-field--no-label">
			<input type="text" class="mdc-text-field__input" aria-label="Label" @input="check_empty" @change="update(index)" v-model="price.offer">
			<div class="mdc-notched-outline">
				<div class="mdc-notched-outline__leading"></div>
				<div class="mdc-notched-outline__trailing"></div>
			</div>
		</div>
		<div class="mdc-text-field mdc-text-field--outlined mdc-text-field--no-label">
			<input type="text" class="mdc-text-field__input" aria-label="Label" @input="check_empty" @change="update(index)" v-model="price.retail" :placeholder="computed_retail(price.offer)">
			<div class="mdc-notched-outline">
				<div class="mdc-notched-outline__leading"></div>
				<div class="mdc-notched-outline__trailing"></div>
			</div>
		</div>
		<div class="mdc-text-field mdc-text-field--outlined mdc-text-field--no-label flex-shrink-1">
			<input type="text" class="mdc-text-field__input" aria-label="Label" @input="check_empty" @change="update(index)" v-model="price.stock">
			<div class="mdc-notched-outline">
				<div class="mdc-notched-outline__leading"></div>
				<div class="mdc-notched-outline__trailing"></div>
			</div>
		</div>
		<div class="flex-shrink-1">
			<button @click.prevent="delete_price(index)" type="button" class="mdc-button mdc-button--error" tabindex="-1">
				<span class="mdc-button__label">删除</span>
			</button>
		</div>
	</div>
	<input type="hidden" name="data" :value="json_price">
</div>
</template>

<script>
import {
	MDCTextField
} from '@material/textfield';

export default {
	props: {
		input: Array,
	},
	data: function() {
		return {
			prices: this.input,
			fill_offer: null,
			fill_retail: null,
			fill_stock: null,
			profit_rate: 10,
		};
	},
	mounted() {
		this.prices.push({});
	},
	computed: {
		json_price: function() {
			return JSON.stringify(prices.filter((price)=>(price.size&&price.offer&&price.retail&&price.stock)));
		}
	},
	watch: {},
	methods: {
		update: function(index) {
			this.clear_empty();
			for (let i in this.prices) {
				if (!this.prices[i].size || !this.prices[i].offer || !this.prices[i].retail || !this.prices[i].stock) {
					continue;
				}
				this.prices[i].size = String(this.prices[i].size).toUpperCase().replace(/，/g,',');
				let offer = this.prices[i].offer;
				let retail = this.prices[i].retail;
				let stock = this.prices[i].stock;
				if(/^[0-9]+%$/.test(retail)){
					retail = this.prices[i].retail = Math.ceil((parseFloat(retail) + 100) * parseFloat(offer) / 1000.0) * 10;
				}
				if (/^[0-9]+[-][0-9]+$/.test(this.prices[i].size)) {
					let [start, end] = this.prices[i].size.split('-');
					this.prices.splice(i, 1);
					for (let j = start; j <= end; j++) {
						for (let k in this.prices) {
							if (this.prices[k].size === j) {
								this.prices.splice(k, 1);
							}
						}
						this.prices.push({
							'size': j,
							'offer': offer,
							'retail': retail,
							'stock': stock,
						});
					}
				} else if (/^[X]*[SML]+[-][X]*[SML]+$/.test(this.prices[i].size)) {
					let [start, end] = this.prices[i].size.split('-');
					this.prices.splice(i, 1);
					let sizes = ['XXS', 'XS', 'S', 'M', 'L', 'XL', 'XXL'];
					if (sizes.includes(start) && sizes.includes(end)) {
						start = sizes.indexOf(start);
						end = sizes.indexOf(end);
						for (let j = start; j <= end; j++) {
							for (let k in this.prices) {
								if (this.prices[k].size === sizes[j]) {
									this.prices.splice(k, 1);
								}
							}
							this.prices.push({
								'size': sizes[j],
								'offer': offer,
								'retail': retail,
								'stock': stock,
							});
						}
					}
				} else if (/^([X]*[SML]+,)+[X]*[SML]+$/.test(this.prices[i].size) || /^([0-9.]+,)+[0-9.]+$/.test(this.prices[i].size)) {
					let sizes = this.prices[i].size.split(',');
					this.prices.splice(i, 1);
					for (let size of sizes) {
						this.prices.push({
							'size': size,
							'offer': offer,
							'retail': retail,
							'stock': stock,
						});
					}
				} else {
					continue;
				}
			}
			this.check_empty();
		},
		delete_price: function(index) {
			this.prices.splice(index, 1);
			this.check_empty();
		},
		check_empty: function() {
			for (let price of this.prices) {
				if (!price.size && !price.offer && !price.retail && !price.stock) {
					return;
				}
			}
			this.prices.push({});
		},
		clear_empty: function() {
			for (let index in this.prices) {
				if (!this.prices[index].size && !this.prices[index].offer && !this.prices[index].retail && !this.prices[index].stock) {
					this.prices.splice(index, 1);
				}
			}
		},
		computed_retail: function(offer) {
			if (offer && offer > 0) {
				return Math.ceil(offer * ( 100 + this.profit_rate ) / 1000) * 10;
			} else {
				return '';
			}
		},
		fill: function() {
			this.clear_empty();
			if (this.fill_offer && this.fill_offer > 0) {
				for (let price of this.prices) {
						price.offer = this.fill_offer;
				}
				this.fill_offer = null;
			}
			if (this.fill_retail) {
				for (let price of this.prices) {
						price.retail = this.fill_retail;
				}
				this.fill_retail = null;
			}
			if (this.fill_stock && this.fill_stock > 0) {
				for (let price of this.prices) {
						price.stock = this.fill_stock;
				}
				this.fill_stock = null;
			}
			this.check_empty();
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
