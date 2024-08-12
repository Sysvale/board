import { IRequestable } from "../../utils/cuids/interfaces/RequestableInterface";

export class Workspace implements IRequestable<Object> {
	private id: string;
	private name: string;
	private status: boolean;
	private teams: Array<any>;

	constructor(args?: any) {
		this.id = args?.id;
		this.name = args?.name;
		this.status = args?.status;
		this.teams = args?.teams;
	}

	asRequestPayload = (): Object => {
		return {
			id: this.id,
			name: this.name,
			status: this.status,
			teamIds: this.teams.map(({ id }) => id),
		};
	}
}