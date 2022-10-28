<template>
	<div class="row justify-content-center">
		<div class="spinner-border" role="status" v-if="loading" :style="{ width: width + 'rem', height: height + 'rem' }">
			<span class="visually-hidden">Loading...</span>
		</div>
	</div>
</template>

<script>
export default {
	name: 'Loading',
	props: {
		loading: {
			type: Boolean,
			default: false
		},
		width: {
			type: Number,
			default: 2
		},
		height: {
			type: Number,
			default: 2
		},
	},
}
</script>