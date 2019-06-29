<template>
<div class="" style="position:relative;">
	<img class="d-block w-100" :src="'/storage/images/' + filename">
	<a href="#" @click.prevent="delete_image" id="delete-link">×</a>
</div>
</template>

<script>
export default {
	props: ['filename', 'id'],
	methods: {
		delete_image: function() {
			axios.delete('/images/' + this.id)
				.then(response => window.location.reload())
				.catch();
		},
	}
}
</script>

<style scoped>
#delete-link {
	position: absolute;
	top: 0;
	right: 0;
	width: 40px;
	line-height: 40px;
	height: 40px;
	font-size: 40px;
	text-align: center;
	color: black;
}

#delete-link:hover {
	text-decoration: none;
}
</style>
