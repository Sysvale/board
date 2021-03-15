<template>
	<v-btn
		:color="value ? activeBackgroundColor : 'gray'"
		title="Tem métrica"
		label
		elevation="0"
		:class="{ 'muted': !value }"
		:style="{
			color: value ? `${activeTextColor}!important` : 'black'
		}"
		@click="$emit('input', !value)"
	>
			<slot>
				switch {{ value }}
			</slot>
	</v-btn>
</template>
<script>
export default {
	props: {
		value: {
			type: Boolean,
			default: false,
		},
		activeBackgroundColor: {
			type: String,
			default: 'green',
		},
		activeTextColor: {
			type: String,
			default: 'blue',
		}
	},
	data() {
		return {
			internalValue: this.value,
		};
	},
	watch: {
		value(newValue) {
			this.internalValue = newValue;
		},
	},
	methods: {
		handleButtonClick() {
			this.$emit('input', !this.internalValue)
		},
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
