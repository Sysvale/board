import { ICuidsPageSettings } from "../../utils/cuids/interfaces/CuidsPageSettingsInterface";

export class MembersPageSettings implements ICuidsPageSettings {
	addItemButtonText = 'Adicionar membro';
	createSuccessFeedbackTitle = 'Sucesso';
	createSuccessFeedbackText = 'Membro criado com sucesso';
	createSuccessFeedbackParams = {};
	deleteSuccessFeedbackText = 'Membro deletado com sucesso';
	deleteSuccessFeedbackTitle = 'Sucesso';
	deleteSuccessFeedbackParams = {};
	deleteConfirmationTitle = 'Tem certeza que deseja deletar?';
	deleteConfirmationText = 'Essa ação não poderá ser desfeita';
	emptyStateImage = '';
	emptyStateDescription = 'Empty state description';
	emptyStateTitle = 'Nenhum membro cadastrado';
	emptyStateText = 'Ainda não ha nenhum membro cadastrado';
	emptyStateActionButtonText = 'Adicionar membro';
	pageTitle = 'Membros';
	pageSubtitle = 'Gerencie membros';
	updateSuccessFeedbackText = 'Membro atualizado com sucesso'; 
	updateSuccessFeedbackTitle = 'Sucesso';
	updateSuccessFeedbackParams = {};
}