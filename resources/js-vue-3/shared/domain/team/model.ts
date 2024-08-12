import { IRequestable } from "../../utils/cuids/interfaces/RequestableInterface";

export class Team implements IRequestable<Object> {
	private id: string;
	private name: string;
	private boardLists: Array<any>;

	constructor(args?: any) {
		this.id = args?.id;
		this.name = args?.name;
		this.boardLists = args?.boardLists;
	}

	asRequestPayload = (): Object => {
		return {
			id: this.id,
			name: this.name,
			boardLists: this.boardLists,
		};
	}
}