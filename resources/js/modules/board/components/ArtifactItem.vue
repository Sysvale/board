<template>
	<v-row class="mx-0 d-flex align-center">
		<div>
			{{ artifact.description }}
		</div>
		<div class="ml-2">
			<link-chip
				:link="artifact.url"
			/>
		</div>
	</v-row>
</template>

<script>
import LinkChip from './LinkChip.vue';

export default {
	components: {
		LinkChip,
	},

	props: {
		artifact: {
			type: Object,
			default: () => {},
		},
	},
};
</script>
