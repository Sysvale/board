import http from '../../../http';
import { 
	DEVELOPMENT,
	TODO,
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
