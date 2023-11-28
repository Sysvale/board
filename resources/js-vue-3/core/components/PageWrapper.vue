<template>
	<div class="page-wrapper">
		<div class="page-wrapper__side-bar">
			<cds-side-bar
				:items="items"
				:active-item="items[0]"
				@sidebar-click="handleSideBarItemClick"
			/>
		</div>
		<div class="page-wrapper__page-content">
			<div class="page-wrapper__page-content--header">
				<cds-page-header
					:title="title"
					:subtitle="subtitle"
				/>
				<slot name="page-title">
					{{ title }}
				</slot>
				<div class="page-wrapper__page-content--header-action">
					<slot name="page-title-action"/>
				</div>
			</div>
			<slot name="page-subtitle">
				{{ subtitle }}
			</slot>
			<slot name="page-body">
				<div class="page-wrapper__page-content--body">
					<slot/>
				</div>
			</slot>
		</div>
	</div>
</template>
<script>
export default {
	props: {
		title: {
			type: String,
		},
		subtitle: {
			type: String,
		}
	},

	data() {
		return {
			items: [
				{
					label: "Workspaces",
					icon: "dashboard-outline",
					type: "route",
					route: {
						path: "/workspace/select",
						name: "dashboard"
					}
				},
				{
					label: "Configurações",
					icon: "settings-outline",
					type: "route",
					items: [
						{
							label: "Membros",
							route: {
								path: "/settings/members",
								name: "members"
							}
						},
						{
							label: "Workspaces",
							route: {
								path: "/settings/workspaces",
								name: "workspaces"
							}
						},
					]
				},
			]
		}
	},

	methods: {
		logout() {
			window.location.href = '/sso/logout';
		},

		handleSideBarItemClick(item) {
			console.log(item);
			switch(item.route.name) {
			case 'dashboard':
			case 'members':
			case 'workspaces':
				return window.location = item.route.path;
			default:
				return;
			}
		}
	}
}
</script>
<style>
body {
	margin: 0!important;
}
</style>
<style lang="scss" scoped>
.page-wrapper {
	 display: flex;
	 &__side-bar {
		position: fixed;
	 }
	 &__page-content {
		padding: 10px;
		margin-left: 242px;
	 }
}
</style>