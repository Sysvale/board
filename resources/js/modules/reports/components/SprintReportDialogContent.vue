<template>
	<div class="px-5 py-5">
		<v-row>
			<v-col>
				<div>
					Início
				</div>
				<v-menu
					v-model="startedAtMenu"
					:close-on-content-click="false"
					:nudge-right="40"
					transition="scale-transition"
					offset-y
					min-width="290px"
					:disabled="readonly"
				>
					<template v-slot:activator="{ on, attrs }">
						<v-text-field
							:value="getSelectedEventFormattedDate(internalValue.startedAt)"
							prepend-icon="insert_invitation"
							readonly
							outlined
							flat
							dense
							:disabled="readonly"
							v-bind="attrs"
							v-on="on"
						/>
					</template>
					<v-date-picker
						v-model="internalValue.startedAt"
						locale="pt-BR"
						no-title
						:max="maxDate"
						@input="startedAtMenu = false"
					/>
				</v-menu>
			</v-col>
			<v-col>
				<div>
					Fim
				</div>
				<v-menu
					v-model="finishedAtMenu"
					:close-on-content-click="false"
					:nudge-right="40"
					transition="scale-transition"
					offset-y
					min-width="290px"
					:disabled="readonly"
				>
					<template v-slot:activator="{ on, attrs }">
						<v-text-field
							:value="getSelectedEventFormattedDate(internalValue.finishedAt)"
							prepend-icon="insert_invitation"
							readonly
							outlined
							flat
							dense
							:disabled="readonly"
							v-bind="attrs"
							v-on="on"
						/>
					</template>
					<v-date-picker
						v-model="internalValue.finishedAt"
						locale="pt-BR"
						no-title
						:max="maxDate"
						@input="finishedAtMenu = false"
					/>
				</v-menu>
			</v-col>
		</v-row>
		<v-row>
			<v-col>
				<div>
					Membros disponíveis
				</div>
				<member-select
					v-model="internalValue.members"
					:disabled="readonly"
					:list-all="readonly"
				/>
			</v-col>
			<v-col>
				<div>
					Qtd. de impedimentos
				</div>
				<v-text-field
					v-model="internalValue.impedimentsQuantity"
					outlined
					type="number"
					flat
					dense
					:rules="[numberRule]"
					class="mb-2"
					:disabled="readonly"
				/>
			</v-col>
		</v-row>
		<v-row>
			<v-col>
				<div class="mb-3">
					Observações
				</div>
				<v-textarea
					v-model="internalValue.notes"
					outlined
					height="60px"
					:disabled="readonly"
				/>
			</v-col>
		</v-row>
		<v-row>
			<v-col>
				<div class="mb-3">
					Sprint backlog
				</div>
				<v-data-table
					:key="tableControl"
					:headers="headers"
					:items="internalValue.cards"
					item-class="text-center"
					class="elevation-1"
					hide-default-footer
					:items-per-page="-1"
				>
					<template
						v-slot:item.title="{ item }"
					>
						<v-tooltip
							bottom
							max-width="250px"
							color="rgba(0, 0, 0, 0.9)"
						>
							<template v-slot:activator="{ on, attrs }">
								<div
									v-bind="attrs"
									class="ellipsis text-center"
									v-on="on"
								>
									{{ item.title }}
								</div>
							</template>
							<span>{{ item.title }}</span>
						</v-tooltip>
					</template>
					<template
						v-slot:item.details="{ item }"
					>
						<div class="d-flex">
							<v-icon
								v-if="item.bimesterGoal"
								class="ml-2"
								small
							>
								my_location
							</v-icon>
							<v-icon
								v-if="item.isRecurrent"
								class="ml-2"
								small
							>
								restore
							</v-icon>
						</div>
					</template>
					<template
						v-slot:item.backlogLabels="{ item }"
					>
						<label-list
							:labels="item.backlogLabels"
							:raw-labels="backlogLabels"
							small
						/>
					</template>
					<template
						v-slot:item.status="{ item }"
					>
						<v-select
							v-model="item.status"
							:items="userStoriesStatusesOptions"
							placeholder="Status"
							return
							outlined
							dense
							flat
							hide-details
							item-text="text"
							item-value="value"
							:disabled="readonly"
							@input="item.shouldBeDeleted = setIfItemShouldBeDeleted(item)"
						/>
					</template>
					<template
						v-slot:item.shouldBeDeleted="{ item }"
					>
						<v-checkbox
							v-model="item.shouldBeDeleted"
						/>
					</template>
				</v-data-table>
			</v-col>
		</v-row>
	</div>
</template>
<script>
import { createNamespacedHelpers, mapState } from 'vuex';
import moment from 'moment';
import {
	options as userStoriesStatusesOptions,
	dictionary as userStoriesStatusesDictionary,
} from '../../../core/constants/userStoryStatuses';

import MemberSelect from '../../board/components/MemberSelect.vue';
import LabelList from '../../board/components/LabelList.vue';

export default {

	components: { MemberSelect, LabelList },

	props: {
		value: {
			type: Object,
			default: () => ({}),
		},
		teamId: {
			type: String,
			default: null,
		},
		readonly: {
			type: Boolean,
			default: false,
		},
	},

	data() {
		return {
			headers: [
				{
					text: 'Descrição',
					value: 'title',
					sortable: false,
				},
				{
					text: 'Pontuação',
					value: 'estimated',
					sortable: false,
				},
				{
					text: 'Detalhes',
					value: 'details',
					sortable: false,
				},
				{
					text: 'Categorias',
					value: 'backlogLabels',
					sortable: false,
				},
				{
					text: 'Estado',
					value: 'status',
					sortable: false,
				},
				!this.readonly ? {
					text: 'Excluir',
					value: 'shouldBeDeleted',
					sortable: false,
				} : null,
			].filter((item) => item),
			startedAtMenu: false,
			finishedAtMenu: false,
			internalValue: {
				...this.value,
				teamId: this.teamId,
			},
			numberRule: (v) => {
				if (Number.isInteger(+v)) return true;
				return 'Insira um valor válido';
			},
			userStoriesStatusesOptions,
			tableControl: 0,
		};
	},

	computed: {
		...mapState('backlogLabels', {
			backlogLabels: ({ items }) => items,
		}),

		maxDate() {
			return moment().toISOString();
		},
	},

	watch: {
		internalValue: {
			handler(newValue) {
				this.$emit('input', newValue);
			},
			deep: true,
		},
	},

	beforeCreate() {
		const { teamId, readonly } = this.$options.propsData;

		if (readonly) return;

		if (teamId) {
			const namespace = `userStories-${teamId}`;
			const {
				mapState: mapStateHelper,
			} = createNamespacedHelpers(namespace);

			this.$options.computed = {
				...mapStateHelper({
					userStories: 'items',
				}),
				...this.$options.computed,
			};
		}
	},

	mounted() {
		if (this.readonly) return;

		this.internalValue.cards = this.userStories.map((item) => ({
			id: item.id,
			title: item.title,
			status: userStoriesStatusesDictionary.IN_PROGRESS,
			shouldBeDeleted: false,
			estimated: item.estimated,
			isRecurrent: item.isRecurrent,
			bimesterGoal: item.bimesterGoal,
			backlogLabels: item.backlogLabels,
		}));

		this.tableControl += 1;
	},

	methods: {
		getSelectedEventFormattedDate(date) {
			return date ? moment(date).locale('pt-BR').format('DD/MM/YY') : '';
		},
		isNaN(value) {
			return parseFloat(value) !== +value;
		},

		setIfItemShouldBeDeleted(item) {
			const { DONE, CANCELED } = userStoriesStatusesDictionary;
			return (item.status === DONE || item.status === CANCELED) && !item.isRecurrent;
		},
	},
};
</script>
<style scoped>
	.ellipsis {
		width: 250px;
		white-space: nowrap;
		overflow: hidden;
		text-overflow: ellipsis;
	}

	.counter{
		background: #E2EAF3;
		color: #1E252C;
		border-radius: 100%;
		display: flex;
		align-items: center;
		justify-content: center;
		font-size: 16px;
		margin: 0 auto;
	}

	.item-class {
		text-align: 'center'!important;
	}
</style>
