import axios from 'axios';
import { IRead } from './interfaces/ReadInterface';
import { IWrite } from './interfaces/WriteInterface';

export abstract class CUIDSService<T> implements IRead<T>, IWrite<T> {

	private resource: string;
	protected httpClient: any;

	constructor(resource: string) {
		this.resource = resource;
		this.httpClient = axios;
	}

	create = async (item: T): Promise<T> => {
		return this.httpClient.post(`/${this.resource}`, item);
	}

	update = async (params?:any): Promise<T> => {
		const { id, ...args } = params;
		return this.httpClient.put(`/${this.resource}/${id}`, args);
	}

	index = async (params?: any): Promise<T[]> => {
		return this.httpClient.get(`/${this.resource}`, { params });
	}

	delete = async (param: any): Promise<T> => {
		const id = typeof param === 'string' ? param : param.id;
		return this.httpClient.delete(`/${this.resource}/${id}`);
	}

	show = async (params?:any): Promise<T> => {
		const { id, ...args } = params;
		return this.httpClient.get(`/${this.resource}/${id}`, { params: args });
	}
}