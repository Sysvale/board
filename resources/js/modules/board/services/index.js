import http from '../../../http';
import { 
	DEVELOPMENT,
	TODO,
	BACKLOG,
} from '../constants/defaultLists';

import {
	USER_STORY,
	USER_STORY_TASK,
	DEV_TASK,
	NOT_PLANNED_TASK,
} from '../constants/cardCategories';

//sprintBacklog makes more sense
export const getUserStories = (teamId) => new Promise((resolve, reject) => {
	setTimeout(() => {
		const data = [
			{
				id: '1',
				title: 'User Story 1',
				category: USER_STORY,
				teamId: 'x',
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
				teamId: 'x',
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

export const getDevBacklog = (teamId) => new Promise((resolve, reject) => {
	setTimeout(() => {
		const data = [
			{
				id: '1a',
				title: 'Dev task 1',
				status: TODO,
				cardId: '1',
				category: DEV_TASK,
			},
			{
				id: '1b',
				title: 'Dev task 2',
				status: DEVELOPMENT,
				cardId: '1',
				category: DEV_TASK,
			},
			{
				id: '1c',
				title: 'Dev task 3',
				status: DEVELOPMENT,
				cardId: '1',
				category: DEV_TASK,
			},
		];
		resolve({data});
	}, 1000);
});

export const getNotPlannedBacklog = (teamId) => new Promise((resolve, reject) => {
	setTimeout(() => {
		const data = [
			{
				id: '1a',
				title: 'Not planned task 1',
				status: TODO,
				cardId: '1',
				category: NOT_PLANNED_TASK,
			},
			{
				id: '1b',
				title: 'Not planned task 2',
				status: DEVELOPMENT,
				cardId: '1',
				category: NOT_PLANNED_TASK,
			},
			{
				id: '1c',
				title: 'Not planned task 3',
				status: DEVELOPMENT,
				cardId: '1',
				category: NOT_PLANNED_TASK,
			},
		];
		resolve({data});
	}, 1000);
});

export const getLists = (boardId) => new Promise((resolve, reject) => {
	setTimeout(() => {
		const data = [
			{
				id: 'listId1',
				name: 'List Name',
				position: 1,
			},
			{
				id: 'listId2',
				name: 'List Name 1',
				position: 2,
			},
			{
				id: 'listId3',
				name: 'List Name 2',
				position: 3,
			},
			{
				id: 'listId4',
				name: 'List Name 3',
				position: 4,
			},
		];

		resolve({ data });
	})
});

export const getCardsByListsIds = (listsIds) => new Promise((resolve, reject) => {
	setTimeout(() => {
		const mockedListsIds = [
			'listId1',
			'listId2',
			'listId3',
		];
		const data = {
			[mockedListsIds[0]]: [
				{
					id: '1a',
					title: 'Not planned task 1',
					category: NOT_PLANNED_TASK,
				},
			],
			[mockedListsIds[1]]: [
				{
					id: '1b',
					title: 'Not planned task 1',
					category: NOT_PLANNED_TASK,
				},
			],
			[mockedListsIds[2]]: [
				{
					id: '1c',
					title: 'Not planned task 1',
					category: NOT_PLANNED_TASK,
				},
				{
					id: '1d',
					title: 'Not planned task 1',
					category: NOT_PLANNED_TASK,
				},
				{
					id: '1e',
					title: 'Not planned task 1',
					category: NOT_PLANNED_TASK,
				},
				{
					id: '1f',
					title: 'Not planned task 1',
					category: NOT_PLANNED_TASK,
				},
			],
		};
		resolve({data});
	}, 1000);
});
