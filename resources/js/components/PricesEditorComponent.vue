<template>
<div class="container-fluid">
	<div v-for="(price,index) in prices" class="row mx-0">
		<div class="col p-0">
			<input @input.prevent="check_empty()" class="form-control text-center rounded-0" type="text" v-model="price.size">
		</div>
		<div class="col p-0">
			<input @input.prevent="check_empty()" @change.prevent="update_price()" class="form-control text-center rounded-0" type="text" v-model="price.cost">
		</div>
		<div class="col p-0">
			<input @input.prevent="check_empty()" class="form-control text-center rounded-0" type="text" v-model="price.resell">
		</div>
		<div class="col p-0">
			<input @input.prevent="check_empty()" class="form-control text-center rounded-0" type="text" v-model="price.retail">
		</div>
		<div class="col-auto p-0 d-flex align-items-center">
			<button @click.prevent="prices.splice(index,1);" type="button" class="btn btn-danger rounded-0 w-100 h-100">Delete</button>
		</div>
	</div>
	<div class="row justify-content-end mt-3">
		<div class="col-auto">
			<button class="mr-2 btn btn-info">Update</button>
			<button class="mr-2 btn btn-danger">Clear</button>
			<button class=" btn btn-danger">Reset</button>
		</div>
	</div>
</div>
</template>

<script>
export default {
	props: {
		input: Array,
	},
	data: function() {
		return {
			prices: this.input,
		};
	},
	mounted() {
		console.log('Component mounted.');
		this.prices.push({});
	},
	computed: {},
	watch: {},
	methods: {
		check_empty: function() {
			for (let price of this.prices) {
				if (!(price.size || price.cost || price.resell || price.retail)) {
					return;
				}
			}
			this.prices.push({});
		},
		update_price: function() {
			console.log('onchange event fired');
			for (let price of this.prices) {
				if (price.cost && !price.resell && !price.retail) {
					price.resell = price.cost * 1.1;
					price.retail = price.cost * 1.1 * 1.2;
				}
			}
		},
	}
}
</script>
