<template>
		<span
			style="position:relative"
			:key="member.id"
			class="member-item d-flex align-center justify-center"
			:title="member.title || member.name"
			:style="avatarStyle"
			@mouseover="deletable ? overlay = true : null"
			@mouseleave="deletable ? overlay = false : null"
		>
			<v-overlay
				v-if="deletable"
				absolute
				:value="overlay"
				color="rgba(0, 0, 0, 0.9)"
			>
				<v-btn
					icon
					@click.stop="$emit('delete', member.id)"
				>
					<v-icon>
						clear
					</v-icon>
				</v-btn>
			</v-overlay>
			<small v-if="!avatarStyle">
				<strong>{{ getFirstLetters(member.name) }}</strong>
			</small>
		</span>
</template>
<script>
import { mapState } from 'vuex';
export default {
	props: {
		member: {
			type: Object,
			default: () => {},
		},
		deletable: {
			type: Boolean,
			default: false,
		},
	},

	data() {
		return {
			overlay: false,
			avatarStyle: null,
		};
	},

	mounted() {
		this.setAvatarStyle(this.member.avatarUrl);
	},

	methods: {
		getFirstLetters(name) {
			const computed = name[0] + ((name.split(' ')[1] ? name.split(' ')[1][0] : false) || name[1]);
			return computed.toUpperCase();
		},
		setAvatarStyle(avatarUrl) {
			let img = new Image();
			img.onload = () => {
				this.avatarStyle = {
					backgroundImage: `url('${avatarUrl}')`,
					backgroundColor: 'transparent!important',
					backgroundSize: 'cover',
					backgroundPosition: 'center',
					border: 'none',
				}
			};
			img.onerror = () => {
				this.avatarStyle = null;
			};
			img.src = avatarUrl;
		},
	}
}
</script>
<style>
.member-item {
	background-color: #dce6ff;
	width: 28px;
	height: 28px;
	border-radius: 50%;
	text-align: center;
	color: #3a7efd;
	border: 1px solid #3a7efd;
}
</style>
