import { ICuidsPageSettings } from "../../utils/cuids/interfaces/CuidsPageSettingsInterface";

export class TeamsPageSettings implements ICuidsPageSettings {
	addItemButtonText = 'Adicionar time';
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
	emptyStateTitle = 'Nenhum time cadastrada';
	emptyStateText = 'Ainda não ha nenhum time cadastrada';
	emptyStateActionButtonText = 'Adicionar time';
	pageTitle = 'Entidades';
	pageSubtitle = 'Gerencie times';
	updateSuccessFeedbackText = 'Entidade atualizada com sucesso'; 
	updateSuccessFeedbackTitle = 'Sucesso';
	updateSuccessFeedbackParams = {};
}