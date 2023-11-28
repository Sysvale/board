<template>
	<PageWrapper>
		<cds-empty-state
			image="https://cdn-icons-png.flaticon.com/512/2748/2748558.png"
			image-description="Página não encontrada"
			title="Página não encontrada"
			text="Não encontramos a página que você procura"
			action-button-text="Voltar ao início"
			action-button-variant="blue"
			@action-button-click="$router.push({ path: '/v2/index/settings' })"
		/>
	</PageWrapper>
</template>