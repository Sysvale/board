import ChecklistItem from '../ChecklistItem';

describe('ChecklistItem class', () => {
	test('if object structure is correct', () => {
		const item = new ChecklistItem({ description: 'test' });

		expect(item).toEqual({ description: 'test', done: false });
	});

	test('if class handles URL descriptions correctly', () => {
		const itemWithUrl = new ChecklistItem({ description: 'www.google.com' });
		const itemWithoutUrl = new ChecklistItem({ description: 'not an URL' });

		expect(itemWithUrl.hasUrlDescription).toBeTruthy();
		expect(itemWithoutUrl.hasUrlDescription).toBeFalsy();
	});

	test('if item content is formatted correctly', () => {
		const itemWithHttpUrl = new ChecklistItem({ description: 'http://www.google.com' });
		const itemWithCommonUrl = new ChecklistItem({ description: 'www.google.com' });
		const itemWithoutUrl = new ChecklistItem({ description: 'not an URL' });

		const expectedUrlDescription = {
			url: 'http://www.google.com',
			text: 'www.google.com',
		};
	
		expect(itemWithHttpUrl.formattedUrlDescription).toMatchObject(expectedUrlDescription);
		expect(itemWithCommonUrl.formattedUrlDescription).toMatchObject(expectedUrlDescription);
		expect(itemWithoutUrl.formattedUrlDescription).toBe('not an URL');
	});
});
