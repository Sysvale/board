<?php

namespace App\Enums;

enum CardTypesEnum : string
{
    case ISSUE_CARD = 'issue-card';
    case TASK_CARD = 'task-card';
    case BACKLOG_CARD = 'backlog-card';

    public function translate(): string
    {
        return match ($this) {
            CardTypesEnum::ISSUE_CARD => 'Issue',
            CardTypesEnum::TASK_CARD => 'Tarefa',
            CardTypesEnum::BACKLOG_CARD => 'Backlog',
        };
    }
}
