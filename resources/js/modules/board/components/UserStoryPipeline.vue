<template>
	<div class="py-2">
		<v-divider
			v-if="!pipelineMode"
		/>
		<div
			v-else
			class="d-flex align-center"
		>
			<user-story-pipeline-item
				:active="development || production || staging"
				value="development"
				status="Em desenvolvimento"
				@click="handleItemClick"
			/>
			<v-divider
				:style="firstDividerStyle"
			/>
			<user-story-pipeline-item
				:active="production || staging"
				value="staging"
				status="Staging"
				@click="handleItemClick"
			/>
			<v-divider
				:style="secondDividerStyle"
			/>
			<user-story-pipeline-item
				:active="production"
				value="production"
				status="Produção"
				@click="handleItemClick"
			/>
		</div>
	</div>
</template>
<script>
import UserStoryPipelineItem from './UserStoryPipelineItem.vue';
export default {
	components: {
		UserStoryPipelineItem,
	},
	props: {
		value: {
			type: String,
			default: 'development',
		},
		pipelineMode: {
			type: Boolean,
			default: false,
		},
	},

	computed: {
		development() {
			return this.value === 'development';
		},
		production() {
			return this.value === 'production';
		},
		staging() {
			return this.value === 'staging';
		},
		firstDividerStyle() {
			if(this.production || this.staging) {
				return {
					borderColor: '#7BD0F4',
				};
			}
		},
		secondDividerStyle() {
			if(this.production) {
				return {
					borderColor: '#7BD0F4',
				};
			}
		},
	},

	methods: {
		handleItemClick(item) {
			this.$emit('input', item);
			this.$emit('status-changed', item);
		},
	}
};
</script>
