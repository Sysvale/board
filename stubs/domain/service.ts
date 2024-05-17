import { CUIDSService } from '../../utils/cuids/CUIDSService';
import { Entity } from './model';

class EntityService extends CUIDSService<Entity> {
	constructor(resource: string) {
		super(resource);
	}
}

export default new EntityService('entities');