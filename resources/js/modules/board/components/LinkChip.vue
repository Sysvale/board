<template>
	<span>
		<a
			v-if="computedLink.label"
			:href="link"
			target="_blank"
			class="badge__link"
		>
			<cds-badge
				:variant="computedLink.variant"
			>
				{{ computedLink.label }}
			</cds-badge>
		</a>

		<v-btn
			v-else
			icon
			:href="link"
			target="_blank"
		>
			<v-icon>open_in_new</v-icon>
		</v-btn>
	</span>
</template>
<script>
export default {
	props: {
		link: {
			type: String,
			default: null,
		},
	},

	computed: {
		computedLink() {
			if (this.link) {
				const url = this.link;

				if (this.isUrlFromThisDomain(url, 'gitlab')) {
					const variant = 'orange';
					const splitedUrl = url.split('/');

					if (this.hasInUrl(url, 'merge_requests')) {
						return {
							label: `!${splitedUrl[splitedUrl.length - 1] || 'merge'}`,
							variant,
						};
					}

					if (this.hasInUrl(url, 'issues')) {
						return {
							label: `#${splitedUrl[splitedUrl.length - 1] || 'merge'}`,
							variant,
						};
					}

					return {
						label: 'gitlab',
						variant,
					};
				}

				if (this.isUrlFromThisDomain(url, 'figma')) {
					return {
						label: 'figma',
						variant: 'gray',
					};
				}

				return {
					variant: 'blue',
				};
			}
			return null;
		},
	},

	methods: {
		isUrlFromThisDomain(url, domain) {
			const regexp = new RegExp(`https?:\/\/(www\.)?(${domain})\.[a-zA-Z0-9()]{1,6}([-a-zA-Z0-9()@:%_\+.~#?&//=]*)`, 'g');
			return !!url.match(regexp);
		},

		hasInUrl(url, string) {
			return url.indexOf(string) > -1;
		},
	},
};
</script>
<style scoped>
	.badge__link {
		text-decoration: none;
	}
</style>
