<template>
		<div>
			<v-rating
				v-model="internalValue"
			>
				<template
					v-slot:item="props">
					<v-tooltip
							bottom
						>
							<template v-slot:activator="{ on, attrs }">
									<v-icon
										v-bind="attrs"
										v-on="on"
										large
										:color="getColor(props)"
										@mouseover="hoveredIndex = props.index"
										@mouseleave="hoveredIndex = -1"
										@click="props.click"
									>
										{{ icon }}
									</v-icon>
							</template>
							{{ tooltipMessage(props.index) }}
						</v-tooltip>
				</template>
			</v-rating>
			<div v-if="tooltipMessage(internalValue - 1)">
				<small>
					<strong>Selecionado: </strong>{{ tooltipMessage(internalValue - 1) }}
				</small>
			</div>
		</div>
</template>
<script>
export default {
	props: {
		icon: {
			type: String,
			default: 'local_fire_department',
		},
		value: {
			type: Number,
			default: -1,
		},
		color: {
			type: String,
			default: 'primary',
		},
		tooltips: {
			type: Array,
			default: [],
		}
	},
	data() {
		return {
			internalValue: this.value,
			hoveredIndex: -1,
		};
	},
	computed: {
		tooltipMessage() {
			return index => {
				return this.tooltips[index];
			}
		},
	},
	watch: {
		internalValue(newValue) {
			this.$emit('input', newValue);
		},
	},
	methods: {
		getColor(props) {
			if(this.hoveredIndex === -1) {
				return props.isFilled ? this.color : '';
			}
			return props.index <= this.hoveredIndex ? this.color : ''
		}
	}
}
</script>
<style scoped>
	.muted {
		opacity: 0.6;
	}
	.muted:hover {
		opacity: 1;
	}
</style>
