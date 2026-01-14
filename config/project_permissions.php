<?php
return [
    'creator' => [
        'project.participant.invite',
        'project.participant.exclude',

        'tasklist.create',
        'tasklist.update',
        'tasklist.delete',

        'task.create',
        'task.update',
        'task.delete',
    ],
    'curator' => [
        'project.participant.invite',
        'project.participant.exclude',

        'tasklist.create',
        'tasklist.update',
        'tasklist.delete',

        'task.create',
        'task.update',
        'task.delete',
    ],
    'executor' => [
        'task.update.tasklist',
        'task.update.status',
        'task.update.executor.self',
    ]
];
