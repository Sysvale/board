<template>
	<PageWrapper
		title="Configurações"
	>
		<cds-card
			imageWidth="300"
			imageHeight="180"
			imageSrc="https://images.unsplash.com/photo-1677391520535-e03e845dec33?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=687&q=80"
			@cardClick="logClick"
		>
			<template #header>
				<div style="margin-bottom: 12px">
					Card Title
				</div>
			</template>
			<template #extra>
				<cds-badge>Action</cds-badge>
			</template>
			<template #body>
				Some quick example text to build on the card title and make up the bulk of the card's content.
			</template>
			<template #footer>
				<cds-button size="md" style="margin-top: 16px">
					Action 1
				</cds-button>
			</template>
		</cds-card>
	</PageWrapper>
</template>