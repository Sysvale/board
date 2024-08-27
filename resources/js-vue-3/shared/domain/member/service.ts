import { CUIDSService } from '../../utils/cuids/CUIDSService';
import { Member } from './entity';

class MemberService extends CUIDSService<Member> {
	constructor(resource: string) {
		super(resource);
	}
}

export default new MemberService('members');