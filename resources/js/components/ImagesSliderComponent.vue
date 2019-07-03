<template>
<div id="images-slider" class="carousel slide" data-touch="true" data-ride="false">
	<div class="carousel-inner">
		<div v-for="(image,index) in images" class="carousel-item">
			<img :src="'/storage/' + image" class="d-block w-100">
		</div>
	</div>
	<div class="row px-2 mb-2 justify-content-center carousel-indicators">

		<a v-for="(image,index) in images" href="#" data-target="#images-slider" :data-slide-to="index" class="col-2 px-2 thumbnail-item" :class="{show:index >=0 && index < max}" v-once>
			<img :src="'/storage/' + image" class="d-block w-100">
		</a>
		<a v-if="total>max" href="#" @click.prevent="thumbnail_prev()" class="thumbnail-control-prev">
			<span class="thumbnail-control-prev-icon" aria-hidden="true"></span>
			<span class="sr-only">Previous</span>
		</a>
		<a v-if="total>max" href="#" @click.prevent="thumbnail_next()" class="thumbnail-control-next">
			<span class="thumbnail-control-next-icon" aria-hidden="true"></span>
			<span class="sr-only">Next</span>
		</a>

	</div>
	<a class="carousel-control-prev" href="#images-slider" role="button" data-slide="prev">
		<span class="carousel-control-prev-icon" aria-hidden="true"></span>
		<span class="sr-only">Previous</span>
	</a>
	<a class="carousel-control-next" href="#images-slider" role="button" data-slide="next">
		<span class="carousel-control-next-icon" aria-hidden="true"></span>
		<span class="sr-only">Next</span>
	</a>
</div>
</template>

<script>
export default {
	props: {
		images: Array,
	},
	data: function() {
		return {
			total: this.images.length,
			active: 0,
			show_range: [],
			max: 5,
		};
	},
	beforeMount: function() {
		for (let i = 0; i < this.max; i++) {
			this.show_range.push(i);
		}
	},
	mounted: function() {
		$('.carousel-item')[0].classList.add('active');
		$('.thumbnail-item')[0].classList.add('active');
	},
	watch: {
		show_range: function() {
			let items = $('.thumbnail-item');
			let first = this.show_range[0];
			let last = this.show_range[this.show_range.length - 1];
			for (let i = 0; i < this.total; i++) {
				if (this.show_range.includes(i)) {
					items[i].classList.add('show');
				} else {
					items[i].classList.remove('show');
				}
			}
		}
	},
	methods: {
		thumbnail_prev: function() {
			let first = this.show_range[0];
			if (first > 0) {
				this.show_range.pop();
				this.show_range.unshift(first - 1);
			} else {
				if (this.total > this.max) {
					this.show_range = [];
					for (let i = this.max; i > 0; i--) {
						this.show_range.push(this.total - i);
					}
				}
			}
		},
		thumbnail_next: function() {
			let last = this.show_range[this.show_range.length - 1];
			if (last < this.total - 1) {
				this.show_range.shift();
				this.show_range.push(last + 1);
			} else {
				this.show_range = [];
				for (let i = 0; i < this.max; i++) {
					this.show_range.push(i);
				}
			}
		},
	}
}
</script>

<style scoped>
.carousel-indicators {
	position: absolute;
	left: 0;
	bottom: 0;
	margin-right: 0;
	margin-left: 0;
	z-index: 2;
	width: 100%;
	overflow: hidden;
}

.thumbnail-item {
	display: none;
	opacity: 0.5;
}

.thumbnail-item.show {
	display: block;
}

.thumbnail-item.active {
	opacity: 1;
}

.thumbnail-control-prev,
.thumbnail-control-next {
	width: 13%;
	position: absolute;
	bottom: 0;
	height: 100%;
	align-items: center;
	justify-content: center;
	display: flex;
	z-index: 3;
	opacity: 0.5;
}

.thumbnail-control-prev:hover,
.thumbnail-control-next:hover,
.thumbnail-control-prev:focus,
.thumbnail-control-next:focus {
	opacity: 0.9;
}

.thumbnail-control-prev {
	left: 0;
}

.thumbnail-control-next {
	right: 0;
}

.thumbnail-control-prev-icon,
.thumbnail-control-next-icon {
	display: inline-block;
	width: 20px;
	height: 20px;
	background: no-repeat 50%/100% 100%;
}

.thumbnail-control-prev-icon {

	background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='%23fff' viewBox='0 0 8 8'%3e%3cpath d='M5.25 0l-4 4 4 4 1.5-1.5-2.5-2.5 2.5-2.5-1.5-1.5z'/%3e%3c/svg%3e");
}

.thumbnail-control-next-icon {
	background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='%23fff' viewBox='0 0 8 8'%3e%3cpath d='M2.75 0l-1.5 1.5 2.5 2.5-2.5 2.5 1.5 1.5 4-4-4-4z'/%3e%3c/svg%3e");
}
</style>
