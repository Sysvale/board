export interface IWrite<T> {
	create(item: T): Promise<T>;
	update(id: string, item: T): Promise<T>;
	delete(id: string): Promise<T>;
}