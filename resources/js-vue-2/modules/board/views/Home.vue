<template>
	<v-container
		fluid
		fill-height
		class="px-2 py-5 text-center"
	>
		<div class="flex-grow-1">
			<div class="d-block mb-5">
				{{ message }}
			</div>
			<div
				v-if="showRetryButton"
			>
				<v-btn
					link
					@click="redirect"
				>
					Tentar novamente
				</v-btn>
			</div>
		</div>
	</v-container>
</template>

<script>
export default {
	data() {
		return {
			showRetryButton: false,
			timeout: null,
			errorAutoRedirect: true,
		};
	},

	computed: {
		message() {
			if(this.showRetryButton) {
				return 'Parece que tivemos algum probleminha rs... Que tal tentar novamente?';
			}
			return 'Preparando os motores...';
		},
	},

	watch: {
		'$router': {
			handler(newValue) {
				if(!!newValue) {
					this.redirect();
				}
			},
			immediate: true,
		}
	},

	mounted() {
		this.timeout = setTimeout(() => {
			if(this.errorAutoRedirect) {
				this.showRetryButton = true;
			}
		}, 3000);
	},

	methods: {
		redirect() {
			this.showRetryButton = false;
			this.$router.replace({
				name: 'workspace.select',
			}).then(() => {
				this.errorAutoRedirect = false;
			});
		},
	},
}
</script>
