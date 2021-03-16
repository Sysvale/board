<template>
	<v-card
		v-if="colapsed"
		class="px-2 py-2 mr-1 ml-1"
		flat
		outlined
		color="#efefef"
		min-height="100%"
	>
		<div class="flex-grow-1 d-flex align-center vertical-text">
			<v-btn
				icon
				small
				@click="colapsed = false"
			>
				<v-icon>
					keyboard_arrow_down
				</v-icon>
			</v-btn>

			<v-tooltip bottom>
				<template v-slot:activator="{ on, attrs }">
					<small
						v-bind="attrs"
						class="mb-3 text-uppercase font-weight-medium text--secondary"
						v-on="on"
					>
						{{ $attrs.name }}
					</small>
				</template>
				<span>{{ $attrs.name }}</span>
			</v-tooltip>

			<v-tooltip top>
				<template v-slot:activator="{ on, attrs }">
					<small
						v-bind="attrs"
						class="mb-3 text--secondary"
						v-on="on"
					>
						{{ $attrs.list.length }}
					</small>
				</template>
				<span>{{ $attrs.list.length }}</span>
			</v-tooltip>

			<v-tooltip
				v-if="hasSomeEstimatedCard && pointsSum"
				top
			>
				<template v-slot:activator="{ on, attrs }">
					<small
						v-bind="attrs"
						class="text--primary"
						v-on="on"
					>
						<strong>{{ pointsSum }}</strong>
					</small>
				</template>

				<span>{{ pointsSum }}</span>
			</v-tooltip>
		</div>
	</v-card>

	<list-container
		v-else
	>
		<header class="mb-2 text--black">
			<div class="d-flex px-2 py-2">
				<v-btn
					icon
					small
					@click="colapsed = true"
				>
					<v-icon>
						keyboard_arrow_right
					</v-icon>
				</v-btn>
				<div class="flex-grow-1 d-flex align-center">
					<span class="mb-0 text-uppercase font-weight-medium text--secondary">
						<span>
							<small>{{ $attrs.name }}</small>
							<span class="ml-3 text--secondary mb-0">
								<small>{{ $attrs.list.length }}</small>
							</span>
						</span>
						<div class="d-flex align-center">
							<div
								v-if="hasSomeEstimatedCard && pointsSum"
								class="d-flex mr-2"
							>
								<small class="text--primary"><strong>{{ pointsSum }}</strong></small>
							</div>
							<v-tooltip
								v-if="isAGoalableList && $attrs.list && $attrs.list.length > 0"
								bottom
							>
								<template v-slot:activator="{ on, attrs }">
									<small
										v-bind="attrs"
										v-on="on"
										class="font-weight-bold metric-ratio-badge px-2"
										:style="{
											color: withMetricRatioConfig.color,
											border: `1px solid ${withMetricRatioConfig.color}`,
											backgroundColor: `${withMetricRatioConfig.color}0A`,
										}"
									>
										<v-icon
											:color="withMetricRatioConfig.color"
										>
											insights
										</v-icon>
										{{ withMetricRatioConfig.value }}
									</small>
								</template>
								{{ withMetricRatioConfig.tooltip }}
							</v-tooltip>
						</div>
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
			</div>
			<div
				v-if="isAGoalableList"
				class="mt-1 mx-1"
			>
				<v-alert
					v-if="!editGoal"
					color="#efefef"
					style="cursor:pointer"
					@click="enableEditGoal"
				>
					<span
						:class="goalAlertTextStyle"
					>
						<v-icon small>flag</v-icon>
						{{ getGoalByKey(keyValue).title || 'Informe aqui o objetivo da sprint' }}
					</span>
				</v-alert>
				<v-textarea
					v-else
					v-model="getGoalByKey(keyValue).title"
					auto-grow
					autofocus
					color="secondary"
					class="pb-0 mx-1"
					@blur="handleEditGoal"
					@keydown.enter="handleEditGoal"
					@keydown.esc="clear"
				/>
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
					:key="`${$attrs.id}-${$attrs.list.length}`"
					v-bind="$attrs"
					v-on="$listeners"
					:style="placeholderStyle"
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
							:list-type="acceptsCardType"
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
import { mapActions, mapGetters } from 'vuex';
import convertKeysToSnakeCase from '../../../core/utils/convertKeysToSnakeCase';
import {
	BACKLOG,
	AVENGERS,
	STEPPER,
	BREAKOUT_ONE,
	SYS_OUT,
	SYS_IN,
} from '../constants/BoardListKeys';
import Card from './Card.vue';
import ListContainer from './ListContainer.vue';
import ListSkeletonLoader from './ListSkeletonLoader.vue';
import { TASK } from '../constants/CardTypes';

export default {
	components: {
		Card,
		ListContainer,
		ListSkeletonLoader,
	},

	props: {
		acceptsCardType: {
			type: String,
			default: TASK,
		},
		keyValue: {
			type: String,
			default: null,
		},
	},

	data() {
		return {
			newCardTitle: null,
			createMode: false,
			colapsed: false,
			editGoal: false,
		};
	},

	computed: {
		...mapGetters('goals', ['getGoalByKey']),

		isAGoalableList() {
			const goalableLists = [
				BACKLOG,
				AVENGERS,
				STEPPER,
				BREAKOUT_ONE,
				SYS_OUT,
				SYS_IN,
			];
			return goalableLists.indexOf(this.keyValue) > -1;
		},

		cardsQuantity() {
			const { length } = this.$attrs.list;

			if (!length) {
				return '0 cartões';
			}

			if (length === 1) {
				return '1 cartão';
			}

			return `${length} cartões`;
		},

		hasSomeEstimatedCard() {
			return this.$attrs.list.reduce((acc, curr) => acc || curr.estimated !== null, false);
		},

		pointsSum() {
			const sum = _.sum(this.$attrs.list.map((card) => +card.estimated || 0));
			return sum ? `${sum} pt${sum === 1 ? '' : 's'}` : null;
		},

		goalAlertTextStyle() {
			if (this.getGoalByKey(this.keyValue).title) {
				return 'subtitle-2';
			}

			return 'grey--text text--darken-1 subtitle-2 font-italic';
		},

		placeholderStyle() {
			if(this.$attrs.list && this.$attrs.list.length > 0) return {};
			return {
				minHeight: '50px',
				border: '1px dashed #ccc',
				borderRadius: '3px',
				backgroundImage: `url('/images/empty-list.svg')`,
				backgroundSize: '57px 14px',
				backgroundPosition: 'center center',
			};
		},

		withMetricRatioConfig() {
			const hasMetricLength = this.$attrs.list.filter((item) => item.hasMetric).length;
			const { length } = this.$attrs.list;
			const ratio = Math.round((hasMetricLength / length) * 100);

			const pluralTranslation = length === 1 ? 'história possui' : 'histórias possuem';
			const tooltip = `${hasMetricLength} de ${length} ${pluralTranslation} métrica`;

			if(ratio < 50) {
				return {
					value: `${ratio}%`,
					color: '#ED3B51',
					tooltip,
				}
			}

			if(ratio >= 50 && ratio < 60) {
				return {
					value: `${ratio}%`,
					color: '#FBAA32',
					tooltip,
				}
			}

			if(ratio >= 60) {
				return {
					value: `${ratio}%`,
					color: '#29A37D',
					tooltip,
				}
			}

			return {
				value: `${ratio}%`,
				color: 'gray',
				tooltip,
			}
		},
	},

	methods: {
		...mapActions('goals', ['updateGoal']),

		handleAdd() {
			if (this.newCardTitle && this.newCardTitle.trim() !== '') {
				this.$emit('add', {
					title: this.newCardTitle,
					boardListId: this.$attrs.id,
					type: this.acceptsCardType,
				});

				this.newCardTitle = null;
			}

			this.createMode = false;
		},

		enableEditGoal() {
			this.editGoal = true;
		},

		handleEditGoal() {
			this.editGoal = false;
			const payload = convertKeysToSnakeCase({
				...this.getGoalByKey(this.keyValue),
			});

			this.updateGoal(payload);
		},

		clear() {
			this.newCardTitle = null;
			this.createMode = false;
			this.editGoal = false;
		},
	},
};

</script>
<style scoped>
.vertical-text {
	writing-mode: vertical-rl;
	text-orientation: mixed;
}

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

.metric-ratio-badge {
	border-radius: 4px;
}
</style>
