<template>
<div class="">
	<div v-for="(price,index) in prices" class="row mx-n1 mx-lg-n3">
		<div class="col text-center py-2 px-1 px-lg-3">
			<input class="form-control" type="text" v-model="price.size">
		</div>
		<div class="col text-center py-2 px-1 px-lg-3">
			<input class="form-control" type="text" v-model="price.cost">
		</div>
		<div class="col text-center py-2 px-1 px-lg-3">
			<input class="form-control" type="text" v-model="price.resell">
		</div>
		<div class="col text-center py-2 px-1 px-lg-3">
			<input class="form-control" type="text" v-model="price.retail">
		</div>
		<div class="col-auto py-2 px-1 px-lg-3 d-flex align-items-center">
			<a href="" class="text-danger" @click.prevent="remove_price(index)">删除</a>
		</div>
	</div>
	<div class="row mx-n1 mx-lg-n3">
		<div class="col text-center py-2 px-1 px-lg-3">
			<input class="form-control" type="text" @change="add_price" v-model="new_price.size">
		</div>
		<div class="col text-center py-2 px-1 px-lg-3">
			<input class="form-control" type="text" @change="add_price" v-model="new_price.cost">
		</div>
		<div class="col text-center py-2 px-1 px-lg-3">
			<input class="form-control" type="text" @change="add_price" v-model="new_price.resell">
		</div>
		<div class="col text-center py-2 px-1 px-lg-3">
			<input class="form-control" type="text" @change="add_price" v-model="new_price.retail">
		</div>
		<div class="col-auto py-2 px-1 px-lg-3 d-flex align-items-center">
			<a href="" class="text-danger" @click.prevent="">删除</a>
		</div>
	</div>
	<div class="row">
		<div class="col-auto text-right mt-2 ml-auto">
			<a href="" @click.prevent="update_prices" class="mr-2 text-primary">更新</a>
			<a href="" @click.prevent="clear_prices" class="mr-2 text-primary">清空</a>
			<a href="" @click.prevent="reset_prices" class="mr-2 text-primary">重置</a>
			<a href="" @click.prevent="submit" class="mr-2 text-primary">保存</a>
		</div>
	</div>
</div>
</template>

<script>
export default {
	props: {
		'input': String,
	},
	data: function() {
		return {
			prices: Object.values(JSON.parse(this.input)),
			new_price: {
				'size': '',
				'cost': '',
				'resell': '',
				'retail': '',
			},
		};
	},
	mounted() {
		console.log('Component mounted.');
	},
	watch: {},
	methods: {
		add_price: function() {
			this.prices.push(this.new_price);
			this.new_price = {
				'size': '',
				'cost': '',
				'resell': '',
				'retail': '',
			};
		},
		remove_price: function(index) {
			this.prices.splice(index, 1)
		},
		update_prices: function() {
			for (let i in this.prices) {
				let cost = this.prices[i].cost;
				let resell = this.prices[i].resell;
				let retail = this.prices[i].retail;
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
				} else if (/^[XSML]+[-][XSML]+$/.test(this.prices[i].size)) {
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
				} else {
					continue;
				}
			}
		},
		clear_prices: function() {
			this.prices = [];
		},
		reset_prices: function() {
			this.prices = Object.values(JSON.parse(this.input));
		},
		submit: function() {
			console.log(JSON.stringify(this.prices));
		}
	}
}
</script>
