<template>
	<v-chip
		v-if="link"
		:href="link"
		target="blank"
		small
		:color="computedLink.color"
		:text-color="computedLink.textColor"
		@click.native.stop
	>
		<v-icon>
			anchor
		</v-icon>
	</v-chip>
</template>
<script>
export default {
	props: {
		link: {
			type: String,
			default: null,
		}
	},

	computed: {
		computedLink() {
			if(this.link) {
				let url = this.link;
				if(this.isUrlFromThisDomain(url, 'gitlab')) {
					const color = '#fc6d26';
					const textColor = 'white';
					const splitedUrl = url.split('/');
					if(this.hasInUrl(url, 'merge_requests')) {
						return {
							label: `!${splitedUrl[splitedUrl.length - 1] || 'merge'}`,
							color,
							textColor,
						};
					}
					if(this.hasInUrl(url, 'issues')) {
						return {
							label: `#${splitedUrl[splitedUrl.length - 1] || 'merge'}`,
							color,
							textColor,
						};
					}
					return {
						label: 'gitlab',
						color,
						textColor,
					};
				}

				if(this.isUrlFromThisDomain(url, 'figma')) {
					return {
						label: 'mockup',
						color: '#021336',
						textColor: '#fff',
					}
				}
				return {
					label: 'link',
					color: 'blue',
					textColor: 'white',
				}
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
		}
	}
}
</script>
