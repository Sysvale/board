import http from '../../../http';
import { 
	DEVELOPMENT,
	TODO,
} from '../constants/defaultLists';

import {
	USER_STORY,
	USER_STORY_TASK,
} from '../constants/cardCategories';

export const getUserStories = (teamId) => new Promise((resolve, reject) => {
	setTimeout(() => {
		const data = [
			{
				id: '1',
				title: 'User Story 1',
				category: USER_STORY,
				tasks: [
					{
						id: '1a',
						title: 'Task A from User Story 1',
						status: TODO,
						cardId: '1',
						category: USER_STORY_TASK,
					},
					{
						id: '1b',
						title: 'Task B from User Story 1',
						status: DEVELOPMENT,
						cardId: '1',
						category: USER_STORY_TASK,
					},
					{
						id: '1c',
						title: 'Task C from User Story 1',
						status: DEVELOPMENT,
						cardId: '1',
						category: USER_STORY_TASK,
					},
				],
			},

			{
				id: '2',
				title: 'User Story 2',
				category: USER_STORY,
				tasks: [
					{
						id: '2a',
						title: 'Task A from User Story 2',
						status: TODO,
						cardId: '2',
						category: USER_STORY_TASK,
					},
					{
						id: '2b',
						title: 'Task B from User Story 2',
						status: TODO,
						cardId: '2',
						category: USER_STORY_TASK,
					},
				],
			},
		];
		resolve({data});
	}, 1000);
});

/*
//plain
		const data = [
			{
				id: '1',
				title: 'User Story 1',
				category: USER_STORY,
			},
			{
				id: '1a',
				title: 'Task A from User Story 1',
				status: TODO,
				cardId: '1',
				category: USER_STORY_TASK,
			},
			{
				id: '1b',
				title: 'Task B from User Story 1',
				status: TODO,
				cardId: '1',
				category: USER_STORY_TASK,
			},

			{
				id: '2',
				title: 'User Story 2',
				category: USER_STORY,
			},
			{
				id: '2a',
				title: 'Task A from User Story 2',
				status: TODO,
				cardId: '2',
				category: USER_STORY_TASK,
			},
			{
				id: '2b',
				title: 'Task B from User Story 2',
				status: TODO,
				cardId: '2',
				category: USER_STORY_TASK,
			},
		];

*/
