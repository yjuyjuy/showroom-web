<template>
<div id="my-carousel" class="my-carousel">
	<div ref="carousel" class="carousel slide" data-touch="true" data-ride="false">
		<div class="carousel-inner">
			<div v-for="(image,index) in images" class="carousel-item">
				<img :src="image" class="d-block w-100">
			</div>
		</div>
	</div>
	<div class="my-carousel__overlay">
		<div class="my-carousel__thumbnails">
			<a href="#" v-for="(image,index) in images" @click.prevent="carousel(index)" class="my-carousel__thumbnail">
				<img :src="image">
			</a>
			<button v-if="total>max" type="button" class="mdc-icon-button material-icons my-carousel__thumbnail-control-prev" @click.prevent="thumbnail_prev()">navigate_before</button>
			<button v-if="total>max" type="button" class="mdc-icon-button material-icons my-carousel__thumbnail-control-next" @click.prevent="thumbnail_next()">navigate_next</button>
		</div>
		<div class="my-carousel__controls">
			<button type="button" class="mdc-icon-button material-icons my-carousel__control-prev" @click.prevent="carousel('prev')">navigate_before</button>
			<button type="button" class="mdc-icon-button material-icons my-carousel__control-next" @click.prevent="carousel('next')">navigate_next</button>
		</div>
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
			total: this.images.length,
			active: 0,
			show_range: [],
			max: 6,
		};
	},
	beforeMount: function() {
		for (let i = 0; i < this.max; i++) {
			this.show_range.push(i);
		}
	},
	mounted: function() {
		if (this.images.length > 0) {
			$('.carousel-item')[0].classList.add('active');
			$('.my-carousel__thumbnail')[0].classList.add('active');
		}
		$('#my-carousel').on('slide.bs.carousel', function(event) {
			$('.my-carousel__thumbnail.active')[0].classList.remove('active');
			$('.my-carousel__thumbnail')[event.to].classList.add('active');
		});
		$('#my-carousel').on('slid.bs.carousel', function(event) {
			$('.carousel').carousel('pause');
		});
	},
	watch: {
		show_range: function() {
			let items = $('.my-carousel__thumbnail');
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
		carousel: function(param) {
			window.$('.carousel').carousel(param);
		},
	}
}
</script>
