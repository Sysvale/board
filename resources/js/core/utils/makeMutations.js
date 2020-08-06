import {
	requestState,
	successState,
	failureState,
} from './requestsStates';

function callbacksResolver() {
	return {
		request: (state) => {
			Object.assign(state, requestState);
		},

		success: (state) => {
			Object.assign(state, successState);
		},

		failure: (state, errors) => {
			Object.assign(state, failureState(errors));
		},
	};
}

export default function makeMutations(requestName) {
	const { request, success, failure } = callbacksResolver();

	return {
		[`${requestName}Request`]: request,
		[`${requestName}Success`]: success,
		[`${requestName}Failure`]: failure,
	};
}
