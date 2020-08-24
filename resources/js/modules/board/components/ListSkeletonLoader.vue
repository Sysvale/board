<template>
	<div>
		<v-card
			v-for="(e, i) in [1, 2]"
			:key="e"
			elevation="0"
			color="#eee"
			:class="{'mt-2': i > 0}"
		>
			<v-card-text>
				<v-skeleton-loader
					type="paragraph"
				/>
			</v-card-text>
		</v-card>
	</div>
</template>
