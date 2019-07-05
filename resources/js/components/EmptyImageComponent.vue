<template>
<div @drop.prevent="dropped($event)" @dragover.prevent>
	<img @click="$refs.fileInput.click()" src="/storage/icons/AddImage.svg">
	<input @change="store_image($event.target.files[0])" type="file" ref="fileInput" style="display:none;">
</div>
</template>

<script>
export default {
	props: ['productId', 'websiteId', 'typeId'],
	methods: {
		dropped: function(evt) {
			if (evt.dataTransfer.files[0]) {
				this.store_image(evt.dataTransfer.files[0]);
			} else {
				if (event.dataTransfer.getData('img_id')) {
					this.move_image(event.dataTransfer.getData('img_id'));
				}
			}
		},
		store_image: function(image) {
			var formData = new FormData();
			formData.append('image', image);
			formData.append('product_id', this.productId);
			formData.append('website_id', this.websiteId);
			formData.append('type_id', this.typeId);
			axios.post('/images', formData, {
					headers: {
						'Content-Type': 'multipart/form-data',
					},
				})
				.then(response => window.location.reload())
				.catch(error => console.log(error));
		},
		move_image: function(id) {
			axios.patch('/images/' + id + '/move', {
					website_id: this.websiteId,
					type_id: this.typeId,
				})
				.then(response => window.location.reload())
				.catch(error => console.log(error));
		}
	},
}
</script>
