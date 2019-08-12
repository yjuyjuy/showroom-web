<template>
<div class="">
	<div v-for="(price,index) in prices" class="row mx-n1 mx-lg-n3">
		<div class="col text-center py-2 px-1 px-lg-3">
			<input class="form-control" @blur="add_price" type="text" v-model="price.size">
		</div>
		<div class="col text-center py-2 px-1 px-lg-3">
			<input class="form-control" @blur="add_price" type="text" v-model="price.cost">
		</div>
		<div class="col text-center py-2 px-1 px-lg-3">
			<input class="form-control" @blur="add_price" type="text" v-model="price.offer">
		</div>
		<div class="col text-center py-2 px-1 px-lg-3">
			<input class="form-control" @blur="add_price" type="text" v-model="price.retail">
		</div>
		<div class="col-auto py-2 px-1 px-lg-3 d-flex align-items-center">
			<a href="" class="text-danger" @click.prevent="delete_price(index)">删除</a>
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
	props: ['input', 'resourceId', 'submitAction', 'productId'],
	data: function() {
		return {
			prices: [],
			vendorId: '',
		};
	},
	mounted() {
		console.log('Component mounted.');
		this.reset_prices();
		console.log(this.prices);
	},
	computed: {},
	watch: {},
	methods: {
		validate() {
			// remove rows with empty fields or invalid types of data
			this.prices = this.prices.filter(price => {
				return (price.size && price.cost && price.offer && price.retail) && (
					(/^[0-9]+$/.test(price.size) || /^[X]*[SML]+$/.test(price.size)) && [price.cost, price.offer, price.retail].every(element => /^[1-9]+[0-9]*$/.test(element))
				);
			});
			let obj = {};
			for (let price of this.prices) {
				obj[price.size] = price;
			}
			this.prices = Object.values(obj);
		},
		clear_empty() {
			this.prices = this.prices.filter(price => price.size || price.cost || price.offer || price.retail);
		},
		add_empty() {
			this.prices.push({
				'size': '',
				'cost': '',
				'offer': '',
				'retail': '',
			});
		},
		add_price: function() {
			this.clear_empty();
			this.add_empty();
		},
		delete_price: function(index) {
			this.prices.splice(index, 1);
		},
		update_prices: function() {
			this.clear_empty();
			for (let i in this.prices) {
				this.prices[i].size = this.prices[i].size.toUpperCase();
				let cost = this.prices[i].cost;
				let offer = this.prices[i].offer;
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
							'offer': offer,
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
								'offer': offer,
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
							'offer': offer,
							'retail': retail,
						});
					}
				} else {
					continue;
				}
			}
			this.add_empty();
		},
		clear_prices: function() {
			this.prices = [];
			this.add_empty();
		},
		reset_prices: function() {
			this.prices = Object.values(JSON.parse(this.input));
			this.add_empty();
		},
		submit: function() {
			this.update_prices();
			this.validate();
			if (this.submitAction === 'update') {
				axios.patch('/prices/' + this.resourceId, {
						data: this.prices,
					})
					.then(response => {
						if (response.data.success && response.data.redirect) {
							window.location = response.data.redirect;
						}
					})
					.catch(errors => {
						if (errors.response.status == 401) {
							window.location = '/login';
						}
					});
			} else if (this.submitAction === 'store') {
				this.vendorId = document.getElementById('vendor-id-selector').value;
				axios.post('/prices', {
						data: this.prices,
						vendor_id: this.vendorId,
						product_id: this.productId
					})
					.then(response => {
						if (response.data.success && response.data.redirect) {
							window.location = response.data.redirect;
						}
					})
					.catch(errors => {
						if (errors.response.status == 401) {
							window.location = '/login';
						}
					});
			}
		},
	}
}
</script>
