<template>
	<list-container>
		<header class="mb-4 text--black">
			<div>
				<span class="mb-0 font-weight-bold d-block">
					{{ title }}
				</span>
				<div class="mt-n2">
					<small class="mt-n2">
						{{ cardsQuantity }}
					</small>
				</div>
			</div>
		</header>
		<v-btn
			outlined
			block
			small
			class="mb-2"
			:disabled="createMode"
			@click="createMode = true"
		>
			<v-icon>add</v-icon>
		</v-btn>
		<v-textarea
			v-if="createMode"
			v-model="newCardTitle"
			solo
			auto-grow
			autofocus
			@blur="save"
			@keydown.enter="save"
			@keydown.esc="clear"
		/>
		<draggable
			v-bind="$attrs"
			v-on="$listeners"
			:key="`${$attrs.id}-${$attrs.list.length}`"
		>
				<card
					v-for="(element, i) in $attrs.list"
					:key="element.id "
					:class="{
						'mt-2': i > 0,
					}"
				>
					{{ element.title }}
				</card>
		</draggable>
	</list-container>
</template>

<script>
import Card from './Card';
import ListContainer from './ListContainer';

export default {
	components: {
		Card,
		ListContainer,
	},
	props: {
		title: {
			type: String,
			default: 'title',
		},
	},

	data() {
		return {
			newCardTitle: null,
			createMode: false,
		};
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

	methods: {
		save() {
			if(this.newCardTitle && this.newCardTitle.trim() !== '') {
				this.$emit('save', {
					title: this.newCardTitle,
					status: this.$attrs.id,
				});
				this.newCardTitle = null;
			}
			this.createMode = false;
		},
		clear() {
			this.newCardTitle = null;
			this.createMode = false;
		}
	},

}
</script>
