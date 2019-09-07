<template>
<div class="carousel">
	
	<div class="carousel__content">		
		<div v-for="(image,index) in images" class="carousel-item" v-bind:class="{ active: index == active }">
			<img :src="image">
		</div>
	</div>
	
	<div class="carousel__controls">
		<button type="button" class="mdc-icon-button material-icons carousel__control-prev" @click="prev_item()">navigate_before</button>
		<button type="button" class="mdc-icon-button material-icons carousel__control-next" @click="next_item()">navigate_next</button>
	</div>
	
</div>
</template>

<script>
export default {
	props: {
		images: Array,
	},
	data: function() {
		return {
			active: 0,
		};
	},
	beforeMount: function() {
		
	},
	mounted: function() {},
	watch: {},
	methods: {
		prev_item: function() {
			this.active -= 1;
			if (this.active < 0) {
				this.active = this.images.length - 1;
			}
		},
		next_item: function() {
			this.active += 1;
			if (this.active >= this.images.length) {
				this.active = 0;
			}
		},
	}
}
</script>
