<template>
<div @drop.prevent="dropped($event)" @dragover.prevent>
	<img @click="$refs.fileInput.click()" data-src="/storage/icons/ImageDropbox.svg" class="lazy">
	<input @change="store_image($event.target.files)" type="file" ref="fileInput" style="display:none;">
</div>
</template>

<script>
export default {
	props: ['productId', 'order'],
	methods: {
		dropped: function(evt) {
			if (evt.dataTransfer.files[0]) {
				this.store_image(evt.dataTransfer.files);
			} else {
				if (event.dataTransfer.getData('img_id')) {
					this.move_image(event.dataTransfer.getData('img_id'));
				}
			}
		},
		store_image: function(files) {
			if (files.length == 1) {
				var formData = new FormData();
				formData.append('image', files[0]);
				formData.append('product_id', this.productId);
				formData.append('order', this.order);
				axios.post('/images', formData, {
						headers: {
							'Content-Type': 'multipart/form-data',
						},
					})
					.then(response => window.location.reload())
					.catch(error => window.alert('action failed'));
			} else {
				var formData = new FormData();
				for (let i = 0; i < files.length; i++) {
					formData.append('images[]', files[i]);
				}
				formData.append('product_id', this.productId);
				axios.post('/images', formData, {
						headers: {
							'Content-Type': 'multipart/form-data',
						},
					})
					.then(response => window.location.reload())
					.catch(error => window.alert('action failed'));
			}
		},
		move_image: function(id) {
			axios.patch('/images/' + id + '/move', {
					order: this.order,
				})
				.then(response => window.location.reload())
				.catch(error => window.alert('action failed'));
		}
	},
}
</script>
