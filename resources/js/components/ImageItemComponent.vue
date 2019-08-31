<template>
<div @dblclick="$refs.fileInput.click()" @dragstart="dragged" @dragover.prevent @drop.prevent="dropped($event)" style="position:relative;">
	<img draggable="true" class="d-block w-100" :src="src">
	<a href="#" @click.prevent="delete_image" id="delete-link">×</a>
	<input @change="replace_image($event.target.files[0])" type="file" ref="fileInput" style="display:none;">
</div>
</template>

<script>
export default {
	props: ['src', 'id'],
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
			axios.post('/images/' + this.id, formData, {
					headers: {
						'Content-Type': 'multipart/form-data',
					},
				})
				.then(response => window.location.reload())
				.catch(error => window.alert('action failed'));
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
				.catch(error => window.alert('action failed'));
		}
	},
}
</script>

<style scoped>
#delete-link {
	position: absolute;
	top: 0;
	right: 0;
	width: 30px;
	line-height: 30px;
	height: 30px;
	font-size: 40px;
	text-align: left;
	vertical-align: baseline;
	color: black;
}

#delete-link:hover {
	text-decoration: none;
}
</style>
