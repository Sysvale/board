<template>
	<div
		class="d-flex"
	>
		<div
			v-if="!select"
			class="d-flex align-center mb-3"
		>
			<member-list
				:members="value"
				deletable
				full
				@deleteItem="$emit('input', value.filter(i => i !== $event))"
			>
				<template v-slot:append>
					<v-btn
						class="ml-1"
						icon
						small
						@click="select = true"
					>
						<v-icon>
							person_add
						</v-icon>
					</v-btn>
				</template>
			</member-list>
		</div>
		<v-autocomplete
			v-else
			ref="memberSelect"
			:value="value"
			:items="members"
			chips
			deletable-chips
			placeholder="Membros"
			multiple
			outlined
			dense
			return
			autofocus
			hide-selected
			item-text="name"
			item-value="id"
			hide-details
			@input="$emit('input', $event)"
			@blur="select = false"
		>
			<template v-slot:selection="{ item }">
				<member-item
					:member="item"
					class="mr-1"
					deletable
					@delete="$emit('input', value.filter(i => i !== item.id))"
				/>
			</template>
		</v-autocomplete>
	</div>
</template>
<script>
import { mapState } from 'vuex';
import MemberItem from './MemberItem';
import MemberList from './MemberList.vue';

export default {
	props: {
		value: {
			type: [Object, String, Array],
			default: null,
		},
	},

	components: {
		MemberItem,
		MemberList,
	},

	data() {
		return {
			select: false,
			autofocus: false,
		};
	},

	computed: {
		...mapState('members', {
			members: 'items',
		}),
	},

	watch: {
		select(newValue) {
			setTimeout(() => {
				this.$refs.memberSelect.isMenuActive = true;
			}, 100);
		}
	}
}
</script>
