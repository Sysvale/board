export interface IRead<T> {
	index(item: T): Promise<T[] | Error>;
	show(item: T): Promise<T | Error>;
}