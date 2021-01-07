<template>
	<div class="position-relative">
		<v-row class="fill-height">
			<v-col>
				<v-overlay
					absolute
					:value="loading"
				>
					<div class="text-center">
						<v-progress-circular
							indeterminate
							size="64"
						></v-progress-circular>
						<div class="mt-3">
							Carregando...
						</div>
					</div>
				</v-overlay>
				<v-sheet height="64">
					<v-toolbar
						flat
					>
						<v-btn
							outlined
							class="mr-4"
							color="grey darken-2"
							@click="setToday"
						>
							Hoje
						</v-btn>
						<v-btn
							fab
							text
							small
							color="grey darken-2"
							@click="prev"
						>
							<v-icon small>
								arrow_back_ios
							</v-icon>
						</v-btn>
						<v-btn
							fab
							text
							small
							color="grey darken-2"
							@click="next"
						>
							<v-icon small>
								arrow_forward_ios
							</v-icon>
						</v-btn>
						<v-toolbar-title v-if="$refs.calendar">
							{{ $refs.calendar.title }}
						</v-toolbar-title>
						<v-spacer></v-spacer>
						<v-btn
							class="mr-4"
							color="primary"
							@click="addNewEvent"
						>
							Adicionar
						</v-btn>
						<v-menu
							bottom
							right
						>
							<template v-slot:activator="{ on, attrs }">
								<v-btn
									outlined
									color="grey darken-2"
									v-bind="attrs"
									v-on="on"
								>
									<span>{{ typeToLabel[type] }}</span>
									<v-icon right>
										keyboard_arrow_down
									</v-icon>
								</v-btn>
							</template>
							<v-list>
								<v-list-item @click="type = 'week'">
									<v-list-item-title>Semana</v-list-item-title>
								</v-list-item>
								<v-list-item @click="type = 'day'">
									<v-list-item-title>Dia</v-list-item-title>
								</v-list-item>
								<v-list-item @click="type = 'month'">
									<v-list-item-title>Mês</v-list-item-title>
								</v-list-item>
							</v-list>
						</v-menu>
					</v-toolbar>
				</v-sheet>
				<v-sheet height="600">
					<v-calendar
						id="calendar"
						ref="calendar"
						v-model="focus"
						color="primary"
						event-color="transparent"
						event-text-color="primary"
						:events="items"
						:type="type"
						:weekdays="weekdays"
						first-time="08:00"
						first-interval="8"
						locale="pt-BR"
						interval-count="9"
						interval-height="80"
						@click:event="showEvent"
						@click:more="viewDay"
						@click:date="viewDay"
					>
					<template v-slot:event="{ event, eventParsed }">
							<div class="px-1 py-1 highlight">
								<div class="d-flex align-center">
									<member-list
										class="mr-2"
										:members="event.members"
									/>
									<div>
										<div><strong>{{ event.name }}</strong></div>
										<div class="event-time text--secondary">
											{{ eventParsed.start.time }} - {{ eventParsed.end.time }}
										</div>
									</div>
								</div>
							</div>
					</template>
					</v-calendar>
					<v-dialog
						v-model="selectedOpen"
						:close-on-content-click="false"
						:activator="selectedElement"
						width="500"
						@click:outside="handleClickOutside"
						@keydown.esc="handleClickOutside"
					>
						<v-card
							class="px-5 py-5"
						>
							<v-layout class="py-5">
								<h3
									v-if="!titleInEditMode && !createMode"
									class="black--text"
									@click="titleInEditMode = true"
								>
									{{ selectedEvent.name }}
								</h3>
								<v-textarea
									v-else
									v-model="cloneTitle"
									flat
									outlined
									auto-grow
									autofocus
									@blur="handleSave"
								/>
							</v-layout>
							<div class="d-flex justify-start flex-grow-1 pb-5">
								<member-list
									:members="selectedEvent.members"
									full
								/>
							</div>
							<v-divider class="py-5"/>
							<div>
								Data:
							</div>
							<v-menu
								v-model="calendarMenu"
								:close-on-content-click="false"
								:nudge-right="40"
								transition="scale-transition"
								offset-y
								min-width="290px"
							>
								<template v-slot:activator="{ on, attrs }">
									<v-text-field
										:value="getSelectedEventFormatedDate(selectedEvent.date)"
										prepend-icon="insert_invitation"
										readonly
										v-bind="attrs"
										v-on="on"
									></v-text-field>
								</template>
								<v-date-picker
									v-model="selectedEvent.date"
									locale="pt-BR"
									no-title
									@input="calendarMenu = false"
								></v-date-picker>
							</v-menu>
							<v-row>
								<v-col>
									<div>
										Início:
									</div>
									<v-menu
										ref="startMenu"
										v-model="startMenu"
										:close-on-content-click="false"
										:nudge-right="40"
										:return-value.sync="selectedEvent.startTime"
										transition="scale-transition"
										offset-y
										max-width="290px"
										min-width="290px"
									>
										<template v-slot:activator="{ on, attrs }">
											<v-text-field
												:value="getSelectedEventFormatedTime(selectedEvent.startTime)"
												prepend-icon="schedule"
												readonly
												v-bind="attrs"
												v-on="on"
											></v-text-field>
										</template>
										<v-time-picker
											v-if="startMenu"
											v-model="selectedEvent.startTime"
											full-width
											format="24hr"
											min="08:00"
											max="17:00"
											@click:minute="$refs.startMenu.save(selectedEvent.startTime)"
										></v-time-picker>
									</v-menu>
								</v-col>
								<v-col>
									<div>
										Fim:
									</div>
									<v-menu
										ref="endMenu"
										v-model="endMenu"
										:close-on-content-click="false"
										:nudge-right="40"
										:return-value.sync="selectedEvent.endTime"
										transition="scale-transition"
										offset-y
										max-width="290px"
										min-width="290px"
									>
										<template v-slot:activator="{ on, attrs }">
											<v-text-field
												:value="getSelectedEventFormatedTime(selectedEvent.endTime)"
												prepend-icon="schedule"
												readonly
												v-bind="attrs"
												v-on="on"
											></v-text-field>
										</template>
										<v-time-picker
											v-if="endMenu"
											v-model="selectedEvent.endTime"
											full-width
											format="24hr"
											max="17:00"
											:min="selectedEvent.startTime || '08:00'"
											@click:minute="$refs.endMenu.save(selectedEvent.endTime)"
										></v-time-picker>
									</v-menu>
								</v-col>
							</v-row>
							<div class="pb-2">
								Membros:
							</div>
							<member-select
								v-model="selectedEvent.members"
							/>
							<v-card-actions
								class="mt-5 d-flex"
								:class="createMode ? 'justify-end' : 'justify-start'"
							>
								<v-btn
									v-if="!createMode"
									outlined
									color="red"
									small
									@click="handleRemove"
								>
									Excluir
								</v-btn>
								<v-btn
									v-else
									:disabled="!shouldBeSaved"
									color="primary"
									small
									@click="handleAdd"
								>
									Salvar
								</v-btn>
							</v-card-actions>
						</v-card>
					</v-dialog>
				</v-sheet>
			</v-col>
		</v-row>
	</div>
</template>
<script>
import { createNamespacedHelpers, mapActions, mapState } from 'vuex';
import MemberList from './MemberList';
import MemberSelect from './MemberSelect';

import {
	getEventsByTeam,
} from '../services/events';

import makeRequestStore from '../../../core/utils/makeRequestStore';
import convertKeysToSnakeCase from '../../../core/utils/convertKeysToSnakeCase';
import convertKeysToCamelCase from '../../../core/utils/convertKeysToCamelCase';

export default {
	components: {
		MemberSelect,
		MemberList
	},

	props: {
		teamId: {
			type: String,
			default: null,
		},
	},

	beforeCreate() {
		let teamId = this.$options.propsData.teamId;

		if(teamId) {
			const modules = [
				{ getEventsByTeam },
			];

			let namespace = `impediments-${teamId}`;

			if(!this.$store.hasModule(namespace)) {
				this.$store.registerModule(namespace, {
					namespaced: true,
					modules: {
						...modules.reduce((acc, module) => ({
							...acc,
							...makeRequestStore(module),
						}), {}),
					},
					state: {
						items: [],
					},
					mutations: {
						setItems(state, payload) {
							state.items = convertKeysToCamelCase(payload);
						},
						addItem(state, payload) {
							state.items = [
								convertKeysToCamelCase(payload),
								...state.items,
							];
						},
						removeItem(state, id) {
							state.items = state.items.filter(item => item.id !== id);
						},
					},
				});
			}
			const {
				mapActions,
				mapMutations,
				mapState,
			} = createNamespacedHelpers(namespace);
	
			this.$options.computed = {
				...mapState({
					items: 'items',
					isFetching: ({ getEventsByTeam }) => getEventsByTeam.isFetching,
				}),
				...this.$options.computed,
			};
	
			this.$options.methods = {
				...mapActions([
					'getEventsByTeam',
				]),

				...mapMutations([
					'setItems',
					'addItem',
					'removeItem',
				]),
				...this.$options.methods,
			};
		}
	},

	data() {
		return {
			calendarMenu: false,
			startMenu: false,
			endMenu: false,
			titleInEditMode: false,
			cloneTitle: null,
			focus: '',
			type: 'week',
			typeToLabel: {
				month: 'Mês',
				week: 'Semana',
				day: 'Dia',
			},
			newItem: {},
			createMode: false,
			selectedEvent: {},
			selectedElement: null,
			selectedOpen: false,
			colors: ['blue', 'indigo', 'deep-purple', 'cyan', 'green', 'orange', 'grey darken-1'],
			names: ['Meeting', 'Holiday', 'PTO', 'Travel', 'Event', 'Birthday', 'Conference', 'Party'],
		};
	},
	computed: {
		...mapState('events', {
			isCreating: ({ createEvent }) => createEvent.isFetching,
			isRemoving: ({ deleteEvent }) => deleteEvent.isFetching,
			isUpdating: ({ updateEvent }) => updateEvent.isFetching,
		}),

		weekdays() {
			if(this.type === 'week') {
				return [1, 2, 3, 4, 5];
			}
		},
		loading() {
			return this.isCreating || this.isRemoving || this.isUpdating;
		},

		shouldBeSaved() {
			return !!this.cloneTitle
			 && !!this.selectedEvent.startTime
			 && !!this.selectedEvent.endTime
			 && !!this.selectedEvent.date;
		},
	},
	
	created() {
		this.getEventsByTeam(this.teamId)
			.then((data) => {
				this.setItems(data);
				this.$refs.calendar.checkChange();
			});
	},

	watch: {
		selectedEvent: {
			handler(newValue, oldValue) {
				if(!!newValue) {
					this.cloneTitle = _.clone(newValue.name);
					if(!this.createMode) {
						this.selectedEvent.startTime = moment(this.selectedEvent.start).format('HH:mm');
						this.selectedEvent.endTime = moment(this.selectedEvent.end).format('HH:mm');
						this.selectedEvent.date = this.selectedEvent.start;
					}
				}
			},
			deep: true,
		},
		items(newValue) {
			this.$emit('changed', newValue);
		},
	},
	methods: {
		...mapActions('events', [
			'createEvent',
			'deleteEvent',
			'updateEvent',
		]),

		viewDay ({ date }) {
			this.focus = date;
			this.type = 'day';
		},

		setToday () {
			this.focus = '';
		},

		prev () {
			this.$refs.calendar.prev();
		},

		next () {
			this.$refs.calendar.next();
		},

		showEvent ({ nativeEvent, event }) {
			const open = () => {
				this.selectedEvent = event
				this.selectedElement = nativeEvent.target
				setTimeout(() => {
					this.selectedOpen = true
				}, 10)
			}

			if (this.selectedOpen) {
				this.selectedOpen = false
				setTimeout(open, 10)
			} else {
				open()
			}

			if(nativeEvent.stopPropagation) {
				nativeEvent.stopPropagation();
			}
		},

		handleSave() {
			this.selectedEvent.name = _.clone(this.cloneTitle);
			this.titleInEditMode = false;
			this.$emit('save');
		},

		getSelectedEventFormatedDate(date) {
			return date ? moment(date).locale('pt-BR').format('DD/MM/YY') : '';
		},

		getSelectedEventFormatedTime(date) {
			return date ? _.clone(moment(date, 'HH:mm').locale('pt-BR').format('HH:mm')) : '';
		},

		getPayload(event) {
			return convertKeysToSnakeCase({
				id: event.id,
				name: event.name,
				members: event.members,
				start: `${moment(event.date).format('yyyy-MM-DD')} ${event.startTime}`,
				end: `${moment(event.date).format('yyyy-MM-DD')} ${event.endTime}`,
				teamId: this.teamId,
			});
		},

		addNewEvent(event) {
			this.showEvent({
				nativeEvent: event.target,
				event: {},
			});
			this.createMode = true;
			this.titleInEditMode = true;
		},

		handleAdd() {
		this.selectedOpen = false;
			this.createEvent(
				this.getPayload(this.selectedEvent)
			).then((data) => {
				this.addItem(data);
				this.createMode = false;
				this.$refs.calendar.checkChange();
			});
		},

		handleRemove() {
			this.selectedOpen = false;
			this.deleteEvent(this.selectedEvent.id).then(() => {
				this.removeItem(this.selectedEvent.id);
				this.$refs.calendar.checkChange();
			})
		},

		handleClickOutside() {
			if(!this.createMode) {
				this.updateEvent(this.getPayload(this.selectedEvent))
					.then((data) => {
						let newEventsList = this.items.map((item) => {
							if(item.id === data.id) {
								item = {
									...data,
								};
							}
							return item;
						})
						this.setItems(newEventsList);
						this.$refs.calendar.checkChange();
					});
			}
			this.createMode = false;
		}
	},
};
</script>
<style>
.position-relative {
	position: relative;
}
/* width */
.v-calendar-daily__scroll-area::-webkit-scrollbar {
	width: 8px;
	height: 100px;
	border-radius: 50px;
	margin-left: -8px;
}

/* Track */
.v-calendar-daily__scroll-area::-webkit-scrollbar-track {
	background: #f1f1f1;
	margin-left: -8px;

}

/* Handle */
.v-calendar-daily__scroll-area::-webkit-scrollbar-thumb {
	background: rgba(0, 0, 0, 0.15);
	border-radius: 5px;
	margin-left: -8px;

}

/* Handle on hover */
.v-calendar-daily__scroll-area::-webkit-scrollbar-thumb:hover {
	background: rgba(0, 0, 0, 0.20);
	margin-left: -8px;
}

.theme--light.v-calendar-events .v-event-timed  {
	border: 0px solid #1976d2!important;
  border-left: 5px solid!important;
	border-radius: 0px!important;
	min-height: 40px;
}

.event-time {
	font-size: 10px;
}

.highlight {
	background: rgba(255, 255, 255, 0.75);
}
</style>
