import { IRequestable } from "../../utils/cuids/interfaces/RequestableInterface";

export class Member implements IRequestable<Object> {
	private id: string;
	private name: string;
	private email: string;
	private teams: Array<any>;
	private avatarUrl: string;

	constructor(args?: any) {
		this.id = args?.id;
		this.name = args?.name;
		this.teams = args?.teams || [];
		this.email = args?.email;
		this.avatarUrl = args?.avatarUrl;
	}

	get teamIds(): Array<String> {
		return this.teams.map(({ id }) => id);
	}

	asRequestPayload = (): Object => {
		return {
			id: this.id,
			name: this.name,
			teamIds: this.teamIds,
			teams: this.teams,
			email: this.email,
			avatar: this.avatarUrl,
		};
	}
}