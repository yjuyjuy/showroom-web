<template>
<div class="row">
	<div class="form-group col">
		<input class="form-control" type="text" name="size[]" id="size" v-model="size">
	</div>

	<div class="form-group col">
		<input class="form-control" type="text" name="cost[]" id="cost" v-model="cost">
	</div>

	<div class="form-group col">
		<input class="form-control" type="text" name="resell[]" id="resell" v-model="resell">
	</div>

	<div class="form-group col">
		<input class="form-control" type="text" name="retail[]" id="retail" v-model="retail">
	</div>
</div>
</template>

<script>
export default {
	props: {
		current_values: '',
	},
	data: function() {
		return {
			size: '',
			cost: '',
			resell: '',
			retail: '',
		};
	},
	mounted() {
		console.log('Component mounted.');
		if (this.current_values) {
			let data = JSON.parse(this.current_values);
			if (data.size) {
				this.size = data.size;
			}
			if (data.cost) {
				this.cost = data.cost;
			}
			if (data.resell) {
				this.resell = data.resell;
			}
			if (data.retail) {
				this.retail = data.retail;
			}
		}
	},
	watch: {
		cost: function() {
			this.resell = Math.floor(this.cost * 0.12) * 10;
		},
		resell: function() {
			this.retail = Math.floor(this.resell * 0.11) * 10;
		},
	},
}
</script>
