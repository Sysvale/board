import { ICuidsPageSettings } from "../../utils/cuids/interfaces/CuidsPageSettingsInterface";

export class TeamsPageSettings implements ICuidsPageSettings {
	addItemButtonText = 'Adicionar time';
	createSuccessFeedbackTitle = 'Sucesso';
	createSuccessFeedbackText = 'Time criado com sucesso';
	createSuccessFeedbackParams = {};
	deleteSuccessFeedbackText = 'Time deletado com sucesso';
	deleteSuccessFeedbackTitle = 'Sucesso';
	deleteSuccessFeedbackParams = {};
	deleteConfirmationTitle = 'Tem certeza que deseja deletar?';
	deleteConfirmationText = 'Essa ação não poderá ser desfeita';
	emptyStateImage = '';
	emptyStateDescription = 'Empty state description';
	emptyStateTitle = 'Nenhum time cadastrado';
	emptyStateText = 'Ainda não há nenhum time cadastrado';
	emptyStateActionButtonText = 'Adicionar time';
	pageTitle = 'Times';
	pageSubtitle = 'Gerencie times';
	updateSuccessFeedbackText = 'Time atualizada com sucesso'; 
	updateSuccessFeedbackTitle = 'Sucesso';
	updateSuccessFeedbackParams = {};
}