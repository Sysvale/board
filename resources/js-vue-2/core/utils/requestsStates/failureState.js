import initialState from './initialState';

const failureState = errors => ({
	...initialState,
	isFetching: false,
	hasFailed: true,
	errors,
});

export default failureState;
