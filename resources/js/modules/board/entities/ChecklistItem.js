export default class ChecklistItem {
	constructor(checklistItem) {
		this.description = checklistItem.description;
		this.done = checklistItem.done || false;
	}

	fullUrl() {
		return this.description.match(/[-a-zA-Z0-9@:%._+~#=]{1,256}\.[a-zA-Z0-9()]{1,6}\b([-a-zA-Z0-9()@:%_+.~#?&//=]*)/g);
	}

	get hasUrlDescription() {
		return this.fullUrl() && this.fullUrl().length > 0;
	}

	get formattedUrlDescription() {
		if (!this.hasUrlDescription) {
			return this.description;
		}

		const [element] = this.fullUrl();

		const url = `http://${element.replace('https', '').replace('http', '')}`;
		const text = element.replace('https', '').replace('http', '');
		return {
			url,
			text,
		};
	}
}
