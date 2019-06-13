<template>
<div class="py-4 my-4 mx-auto col-10 col-md-8 border">

	<div class="form-group">
		<label for="sizes">尺码</label>
		<input class="form-control" type="text" name="" id="sizes" v-model="sizes">
	</div>

	<div class="form-group">
		<label for="cost">成本</label>
		<input class="form-control" type="text" id="cost" v-model="cost">
	</div>

	<div class="form-group">
		<label for="resell">调货价</label>
		<input class="form-control" type="text" id="resell" v-model="resell">
	</div>

	<div class="form-group">
		<label for="sale">尺码</label>
		<input class="form-control" type="text" id="sale" v-model="sale">
	</div>


</div>
</template>

<script>
export default {
	props: [],
	data: function() {
		return {
			sizes: 'XXS',
			cost: '',
			resell: '',
			sale: '',
		};
	},
	mounted() {
		console.log('Component mounted.')
	},
	computed: {

	},

}
</script>
