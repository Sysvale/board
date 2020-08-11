<template>
	<list-container>
		<header class="mb-4 text--black">
			<div>
				<span class="mb-0 font-weight-bold d-block">
					{{ $attrs.title }}
				</span>
				<div class="mt-n2">
					<small class="mt-n2">
						{{ cardsQuantity }}
					</small>
				</div>
			</div>
		</header>
		<v-btn
			v-if="!createMode"
			block
			small
			depressed
			:disabled="$attrs.loading"
			class="mb-2"
			color="white"
			@click="createMode = true"
		>
			<v-icon
				color="rgba(0, 0, 0, 0.5)"
			>
				add
			</v-icon>
		</v-btn>
		<v-textarea
			v-else
			v-model="newCardTitle"
			solo
			auto-grow
			autofocus
			@blur="save"
			@keydown.enter="save"
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
				<div
					v-for="(item, i) in $attrs.list"
					:key="item.id"
					:class="{
						'mt-2': i > 0,
					}"
				>
					<card
						:item="item"
						@save="save"
						@delete="$emit('delete', {
							id: item.id,
							listId: $attrs.id
						})"
					>
						{{ item.title }}
					</card>
				</div>
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
		save() {
			if(this.newCardTitle && this.newCardTitle.trim() !== '') {
				this.$emit('save', {
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
