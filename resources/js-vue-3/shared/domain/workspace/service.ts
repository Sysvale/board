import { CUIDSService } from '../../utils/cuids/CUIDSService';
import { Workspace } from './model';

class WorkspaceService extends CUIDSService<Workspace> {
	constructor(resource: string) {
		super(resource);
	}
}

export default new WorkspaceService('workspaces');