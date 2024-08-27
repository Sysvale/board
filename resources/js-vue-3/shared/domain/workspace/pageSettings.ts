import { ICuidsPageSettings } from "../../utils/cuids/interfaces/CuidsPageSettingsInterface";

export class WorkspacesPageSettings implements ICuidsPageSettings {
	addItemButtonText = 'Adicionar workspace';
	createSuccessFeedbackTitle = 'Sucesso';
	createSuccessFeedbackText = 'Workspace criado com sucesso';
	createSuccessFeedbackParams = {};
	deleteSuccessFeedbackText = 'Workspace deletado com sucesso';
	deleteSuccessFeedbackTitle = 'Sucesso';
	deleteSuccessFeedbackParams = {};
	deleteConfirmationTitle = 'Tem certeza que deseja deletar o workspace?';
	deleteConfirmationText = 'Essa ação não poderá ser desfeita';
	emptyStateImage = '';
	emptyStateDescription = 'Ainda não há nenhum Workspace';
	emptyStateTitle = 'Nenhum workspace cadastrada';
	emptyStateText = 'Ainda não ha nenhum workspace cadastrada';
	emptyStateActionButtonText = 'Adicionar workspace';
	pageTitle = 'Workspaces';
	pageSubtitle = 'Gerencie workspaces';
	updateSuccessFeedbackText = 'Workspace atualizada com sucesso'; 
	updateSuccessFeedbackTitle = 'Sucesso';
	updateSuccessFeedbackParams = {};
}