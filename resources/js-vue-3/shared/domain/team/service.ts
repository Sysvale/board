import { CUIDSService } from '../../utils/cuids/CUIDSService';
import { Team } from './model';

class TeamService extends CUIDSService<Team> {
	constructor(resource: string) {
		super(resource);
	}
}

export default new TeamService('teams');