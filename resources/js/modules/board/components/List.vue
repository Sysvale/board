<template>
	<list-container>
		<header class="mb-2 text--black d-flex">
			<div class="flex-grow-1">
				<span class="mb-0 font-weight-bold d-block">
					{{ $attrs.title }}
				</span>
				<div class="mt-n2">
					<small class="mt-n2">
						{{ cardsQuantity }}
					</small>
				</div>
			</div>
			<div class="d-flex justify-end">
				<v-btn
					block
					small
					depressed
					:disabled="$attrs.loading"
					color="white"
					class="px-0"
					@click="createMode = true"
				>
					<v-icon
						color="rgba(0, 0, 0, 0.5)"
					>
						add
					</v-icon>
				</v-btn>
			</div>
		</header>
		<v-textarea
			v-if="createMode"
			v-model="newCardTitle"
			solo
			auto-grow
			autofocus
			class="mb-0 pb-0"
			@blur="handleAdd"
			@keydown.enter="handleAdd"
			@keydown.esc="clear"
		/>
		<v-fade-transition
			hide-on-leave
		>
			<list-skeleton-loader
				v-if="$attrs.loading"
			/>
			<draggable
				v-else
				v-bind="$attrs"
				v-on="$listeners"
				:key="`${$attrs.id}-${$attrs.list.length}`"
			>
				<component
					:is="true ? 'v-flex' : 'div'"
					v-for="(item, i) in $attrs.list"
					:key="item.id"
					:class="{
						'mt-3': i > 0,
					}"
				>
					<card
						:item="item"
						@save="handleAdd"
						@delete="$emit('delete', {
							id: item.id,
							listId: $attrs.id
						})"
					>
						{{ item.title }}
					</card>
				</component>
			</draggable>
		</v-fade-transition>
	</list-container>
</template>

<script>
import Card from './Card';
import ListContainer from './ListContainer';
import ListSkeletonLoader from './ListSkeletonLoader';

export default {
	components: {
		Card,
		ListContainer,
		ListSkeletonLoader,
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
		handleAdd() {
			if(this.newCardTitle && this.newCardTitle.trim() !== '') {
				this.$emit('add', {
					title: this.newCardTitle,
					listId: this.$attrs.id,
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
