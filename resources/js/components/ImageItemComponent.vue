<template>
<div @dragstart="dragged" @dragover.prevent @drop.prevent="dropped($event)" style="position:relative;">
	<img draggable="true" class="d-block w-100" :src="'/storage/images/' + filename">
	<a href="#" @click.prevent="delete_image" id="delete-link">×</a>
</div>
</template>

<script>
export default {
	props: ['filename', 'id'],
	methods: {
		dragged: function(event) {
			event.dataTransfer.setData('img_id', this.id);
		},
		dropped: function(evt) {
			if (evt.dataTransfer.files[0]) {
				this.replace_image(evt.dataTransfer.files[0]);
			} else {
				if (event.dataTransfer.getData('img_id')) {
					this.swap_image(event.dataTransfer.getData('img_id'));
				}
			}
		},
		replace_image: function(image) {
			var formData = new FormData();
			formData.append('image', image);
			formData.append('_method', 'patch');
			console.log(formData);
			axios.post('/images/' + this.id, formData, {
					headers: {
						'Content-Type': 'multipart/form-data',
					},
				})
				.then(response => window.location.reload())
				.catch(error => console.log(error));
		},
		delete_image: function() {
			axios.delete('/images/' + this.id)
				.then(response => window.location.reload())
				.catch();
		},
		swap_image: function(id) {
			if (id == this.id) {
				return;
			}
			axios.patch('/images/swap', {
					image_id1: this.id,
					image_id2: id,
				})
				.then(response => window.location.reload())
				.catch(error => console.log(error));
		}
	},
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
