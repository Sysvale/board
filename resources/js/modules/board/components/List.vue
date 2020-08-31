<template>
	<list-container>
		<header class="mb-2 px-2 py-2 text--black d-flex">
			<div class="flex-grow-1 d-flex align-items-center">
				<span class="mb-0 text-uppercase font-weight-medium text--secondary ">
					<small>{{ $attrs.title }}</small>
				</span>
				<span class="ml-3 text--secondary mb-0">
					<small>{{ $attrs.list.length }}</small>
				</span>
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
			class="mb-n5 pb-0 mx-1"
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
			<div
				v-else
				id="cards-container"
				class="pb-1"
			>
				<draggable
					v-bind="$attrs"
					v-on="$listeners"
					:key="`${$attrs.id}-${$attrs.list.length}`"
				>
						<component
							:is="true ? 'v-flex' : 'div'"
							v-for="(item, i) in $attrs.list"
							:key="item.id"
							:class="{
								'mt-2': i > 0,
							}"
							class="mx-1"
						>
							<card
								:item="item"
								@save="handleAdd"
								@delete="$emit('delete', {
									id: item.id,
									boardListId: $attrs.id
								})"
							>
								{{ item.title }}
							</card>
						</component>
				</draggable>
			</div>
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
					boardListId: this.$attrs.id,
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
<style scoped>
#cards-container {
	max-height: 60vh;
	overflow-y: auto;
}
/* width */
#cards-container::-webkit-scrollbar {
	width: 8px;
	height: 100px;
	border-radius: 50px;
}

/* Track */
#cards-container::-webkit-scrollbar-track {
	background: #f1f1f1;
}

/* Handle */
#cards-container::-webkit-scrollbar-thumb {
	background: rgba(0, 0, 0, 0.15);
	border-radius: 5px;
}

/* Handle on hover */
#cards-container::-webkit-scrollbar-thumb:hover {
	background: rgba(0, 0, 0, 0.20);
}
</style>
