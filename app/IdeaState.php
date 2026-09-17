<?php

namespace App;

enum IdeaState: string
{
    case PENDING = 'pending';
    case IN_PROGRESS = 'in_progress';
    case COMPLETED = 'completed';

    public function label(): string {
        return match ($this) {
            self::PENDING => 'Is Pending',
            self::IN_PROGRESS => 'Is In Progress',
            self::COMPLETED => 'Is Completed',
        };
    }
}
