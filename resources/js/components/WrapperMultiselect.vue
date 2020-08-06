<template>
	<ValidationProvider
		:rules="rules"
		:name="$attrs.name"
		v-slot="{ errors}"
	>
		<b-form-group>
			<multiselect
				v-model="selected"
				v-bind="$attrs"
				:show-labels="false"
				:options="options"
				:track-by="trackBy"
				:allow-empty="$attrs['allow-empty']"
				:class="style"
				v-on="$listeners"
				@search-change="queryString = $event"
			>
				<span
					slot="noOptions"
				>
					Não há nenhuma opção para ser exibida.
				</span>
				<span
					slot="noResult"
				>
					Nenhum resultado encontrado para: "<strong>{{ queryString }} </strong>"
				</span>
			</multiselect>
			<b-form-invalid-feedback
				v-if="errors[0]"
				class="d-block"
			>
				{{ errors[0] }}
			</b-form-invalid-feedback>
		</b-form-group>
	</ValidationProvider>
</template>
<script>

import Multiselect from 'vue-multiselect';

export default {
	components: {
		Multiselect,
	},
	inheritAttrs: false,
	props: {
		options: {
			type: Array,
			default: () => [],
		},
		value: {
			type: [String, Number, Boolean, Array, Object],
		},
		trackBy: {
			type: String,
			required: false,
		},
		rules: {
			type: [Object, String],
			default: '',
		},
	},

	data() {
		return {
			selectValue: this.value,
			queryString: '',
		};
	},

	computed: {
		state() {
			return this.error ? 'is-invalid' : null;
		},

		error() {
			if (typeof this.errors.items != 'undefined') {
				const [error] = this.errors.items.filter(item => item.field === this.$attrs.name);
				return error;
			}
			return false;
		},

		selected: {
			get() {
				return this.$attrs.multiple
					? this.selectValue
					: this.findOption(this.selectValue);
			},
			set(v) {
				this.setUp(v);
			},
		},
		forwardListeners() {
			const { input, ...listeners } = this.$listeners;
			return listeners;
		},

		style() {
			return {
				'multiselect-sm': this.$attrs.size === 'sm',
				'multiselect-lg': this.$attrs.size === 'lg',
				'h-auto': this.$attrs.multiple,
			};
		},
	},
	watch: {
		value: {
			handler(newValue, oldValue) {
				if (!_.isEqual(newValue, oldValue)) {
					this.setUp(newValue);
				}
			},
			deep: true,
			immediate: true,
		},
	},
	beforeMount() {
		if (this.$attrs.multiple) {
			this.selectValue = !this.value ? [] : this.value;
		} else {
			this.selectValue = !this.value ? null : this.value[this.trackBy];
		}
	},
	methods: {
		findOption(value) {
			if (this.$attrs.multiple) {
				return this.options.find(option => option[this.trackBy] === value[this.trackBy]);
			}

			return this.options.find(option => option[this.trackBy] === value);
		},
		setUp(v) {
			if (this.$attrs.multiple) {
				this.selectValue = !v ? [] : v;
			} else {
				this.selectValue = !v ? null : v[this.trackBy];
			}
			this.$emit('option-changed', v);
		},
	},
};
</script>
