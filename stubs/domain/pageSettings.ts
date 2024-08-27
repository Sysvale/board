import { ICuidsPageSettings } from "../../utils/cuids/interfaces/CuidsPageSettingsInterface";

export class EntitiesPageSettings implements ICuidsPageSettings {
	addItemButtonText = 'Adicionar entidade';
	createSuccessFeedbackTitle = 'Sucesso';
	createSuccessFeedbackText = 'Entidade criada com sucesso';
	createSuccessFeedbackParams = {};
	deleteSuccessFeedbackText = 'Entidade deletada com sucesso';
	deleteSuccessFeedbackTitle = 'Sucesso';
	deleteSuccessFeedbackParams = {};
	deleteConfirmationTitle = 'Tem certeza que deseja deletar?';
	deleteConfirmationText = 'Essa ação não poderá ser desfeita';
	emptyStateImage = '';
	emptyStateDescription = 'Empty state description';
	emptyStateTitle = 'Nenhum entidade cadastrada';
	emptyStateText = 'Ainda não ha nenhum entidade cadastrada';
	emptyStateActionButtonText = 'Adicionar entidade';
	pageTitle = 'Entidades';
	pageSubtitle = 'Gerencie entidades';
	updateSuccessFeedbackText = 'Entidade atualizada com sucesso'; 
	updateSuccessFeedbackTitle = 'Sucesso';
	updateSuccessFeedbackParams = {};
}