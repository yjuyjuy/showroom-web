<template>
<div class="price-editor">
	<div v-for="(price,index) in prices" class="d-flex price-editor__row">
		<div class="mdc-text-field mdc-text-field--outlined mdc-text-field--no-label">
			<input type="text" class="mdc-text-field__input" aria-label="Label" @input="check_empty" @change="update(index)" v-model="price.size" :autofocus="index == 0" spellcheck="false">
			<div class="mdc-notched-outline">
				<div class="mdc-notched-outline__leading"></div>
				<div class="mdc-notched-outline__trailing"></div>
			</div>
		</div>
		<div class="mdc-text-field mdc-text-field--outlined mdc-text-field--no-label">
			<input type="text" class="mdc-text-field__input" aria-label="Label" @input="check_empty" @change="update(index)" v-model="price.cost">
			<div class="mdc-notched-outline">
				<div class="mdc-notched-outline__leading"></div>
				<div class="mdc-notched-outline__trailing"></div>
			</div>
		</div>
		<div class="mdc-text-field mdc-text-field--outlined mdc-text-field--no-label">
			<input type="text" class="mdc-text-field__input" aria-label="Label" @input="check_empty" @change="update(index)" @dblclick="apply_computed" v-model="price.resell" :placeholder="computed_resell(index)">
			<div class="mdc-notched-outline">
				<div class="mdc-notched-outline__leading"></div>
				<div class="mdc-notched-outline__trailing"></div>
			</div>
		</div>
		<div class="mdc-text-field mdc-text-field--outlined mdc-text-field--no-label">
			<input type="text" class="mdc-text-field__input" aria-label="Label" @input="check_empty" @change="update(index)" @dblclick="apply_computed" v-model="price.retail" :placeholder="computed_retail(index)">
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
	<input type="hidden" name="data" :value="JSON.stringify(prices.filter(price=>(price.size&&price.cost&&price.resell&&price.retail)))">
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
			json_prices: '',
			mdcTextField: undefined,
		};
	},
	mounted() {
		console.log('Component mounted.');
		this.prices.push({});
	},
	computed: {},
	watch: {},
	methods: {
		update: function(index) {
			this.clear_empty();
			for (let i in this.prices) {
				if (!this.prices[i].size || !this.prices[i].cost || !this.prices[i].resell || !this.prices[i].retail) {
					continue;
				}
				this.prices[i].size = this.prices[i].size.toUpperCase();
				let cost = this.prices[i].cost;
				let resell = this.prices[i].resell;
				let retail = this.prices[i].retail;
				if(/^[1-9]+[0-9]*%$/.test(resell)){
					resell = this.prices[i].resell = Math.ceil((parseFloat(resell) / 100.0 + 1.0) * cost / 10.0) * 10;
				}
				if(/^[1-9]+[0-9]*%$/.test(retail)){
					retail = this.prices[i].retail = Math.ceil((parseFloat(retail) / 100.0 + 1.0) * resell / 10.0) * 10;
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
							'cost': cost,
							'resell': resell,
							'retail': retail,
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
								'cost': cost,
								'resell': resell,
								'retail': retail,
							});
						}
					}
				} else if (/^([X]*[SML]+,)+[X]*[SML]+$/.test(this.prices[i].size) || /^([0-9]+,)[0-9]+$/.test(this.prices[i].size)) {
					let sizes = this.prices[i].size.split(',');
					this.prices.splice(i, 1);
					for (let size of sizes) {
						this.prices.push({
							'size': size,
							'cost': cost,
							'resell': resell,
							'retail': retail,
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
				if (!price.size && !price.cost && !price.resell && !price.retail) {
					return;
				}
			}
			this.prices.push({});
		},
		clear_empty: function() {
			for (let index in this.prices) {
				if (!this.prices[index].size && !this.prices[index].cost && !this.prices[index].resell && !this.prices[index].retail) {
					this.prices.splice(index, 1);
				}
			}
		},
		computed_resell: function(index) {
			if (this.prices[index].cost) {
				return Math.ceil(this.prices[index].cost * 0.11) * 10;
			} else {
				return '';
			}
		},
		computed_retail: function(index) {
			if (this.prices[index].cost) {
				return Math.ceil(this.prices[index].cost * 1.1 * 0.11) * 10;
			} else {
				return '';
			}
		},
		apply_computed: function(event) {
			event.target.value = event.target.placeholder;
		},
		prepare: function(evt) {
			console.log('submit event fired');
			evt.preventDefault();
			for (let index in this.prices) {
				if (!this.prices[index].size && !this.prices[index].cost && !this.prices[index].resell && !this.prices[index].retail) {
					this.prices.splice(index, 1);
				} else if (this.prices[index].size && this.prices[index].cost && this.prices[index].resell && this.prices[index].retail) {
					continue;
				} else if (this.prices[index].size && this.prices[index].cost) {
					if (!this.prices[index].resell) {
						this.prices[index].resell = computed_resell(index);
					}
					if (!this.prices[index].retail) {
						this.prices[index].retail = computed_retail(index);
					}
				} else {
					this.prices.splice(index, 1);
				}
			}
			this.json_prices = JSON.stringify(this.prices);
		},
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
