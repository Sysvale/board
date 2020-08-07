<template>
	<div>
		<div class="mb-4 text--black">
			<span class="mb-0 font-weight-bold d-block">
				{{ title }}
			</span>
			<div class="mt-n2">
				<small class="mt-n2">
					{{ cardsQuantity }}
				</small>
			</div>
		</div>
		<draggable
			v-bind="$attrs"
			@start="drag=true"
			@end="drag=false"
			:class="{
				'd-flex': horizontal,
			}"
		>
				<card
					v-for="(element, i) in $attrs.list"
					:key="element.id + i"
					:class="{
						'mt-2': i > 0 && !horizontal,
						'ml-2': i > 0 && horizontal,
						'horizontal': horizontal,
					}"
				>
					<v-card-text
						class="text--black"
					>
						{{ element.title }}
					</v-card-text>
				</card>
		</draggable>
	</div>
</template>

<script>
import Card from './Card';
export default {
	components: {
		Card,
	},
	props: {
		title: {
			type: String,
			default: 'title',
		},
		horizontal: {
			type: Boolean,
			default: false,
		}
	},

	computed: {
		cardsQuantity() {
			const { length } = this.$attrs.list;
			if(!length) {
				return '0 cartões';
			}
			if(length === 1) {
				return '1 cartão';
			}
			return `${length} cartões`;
		}
	},
}
</script>
<style scoped>
.horizontal {
	min-width: 251px;
	max-width: 251px;
}
</style>
