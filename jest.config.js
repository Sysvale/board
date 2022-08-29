module.exports = {
	transform: {
		'.*\\.(vue)$': '<rootDir>/node_modules/vue-jest',
		'^.+\\.js$': '<rootDir>/node_modules/babel-jest',
		'.+\\.(css|styl|less|sass|scss|png|jpg|svg|ttf|woff|woff2)$': '<rootDir>/node_modules/jest-transform-stub',
	},
	testEnvironment: 'jsdom',
	transformIgnorePatterns: [
		'<rootDir>/node_modules/',
	],
	testPathIgnorePatterns: [
		'<rootDir>/.*/__tests__/__data__/.*',
	],
	moduleNameMapper: {
		'.+\\.(css|styl|less|sass|scss|png|jpg|svg|ttf|woff|woff2)$': '<rootDir>/node_modules/jest-transform-stub',
	},
};
