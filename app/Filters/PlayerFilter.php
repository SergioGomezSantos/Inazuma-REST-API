<?php

namespace App\Filters;

use App\Filters\RelationApiFilter;

class PlayerFilter extends RelationApiFilter
{

    protected $safeParams = [
        'name' => ['eq'],
        'fullName' => ['eq'],
        'position' => ['eq', 'ne'],
        'element' => ['eq', 'ne'],
        'originalTeam' => ['eq', 'ne'],
        'GP' => ['eq', 'lt', 'lte', 'gt', 'gte'],
        'TP' => ['eq', 'lt', 'lte', 'gt', 'gte'],
        'kick' => ['eq', 'lt', 'lte', 'gt', 'gte'],
        'body' => ['eq', 'lt', 'lte', 'gt', 'gte'],
        'control' => ['eq', 'lt', 'lte', 'gt', 'gte'],
        'guard' => ['eq', 'lt', 'lte', 'gt', 'gte'],
        'speed' => ['eq', 'lt', 'lte', 'gt', 'gte'],
        'stamina' => ['eq', 'lt', 'lte', 'gt', 'gte'],
        'guts' => ['eq', 'lt', 'lte', 'gt', 'gte'],
        'freedom' => ['eq', 'lt', 'lte', 'gt', 'gte'],
        'technique' => ['eq']
    ];

    protected $columnMap = [
        'fullName' => 'full_name',
        'originalTeam' => 'original_team'
    ];

    protected $operatorMap = [
        'eq' => '=',
        'lt' => '<',
        'lte' => '<=',
        'gt' => '>',
        'gte' => '>=',
    ];

    protected $relationMap = [
        'GP' => [
            'relation' => 'stats',
            'column' => 'GP'
        ],
        'TP' => [
            'relation' => 'stats',
            'column' => 'TP'
        ],
        'kick' => [
            'relation' => 'stats',
            'column' => 'kick'
        ],
        'body' => [
            'relation' => 'stats',
            'column' => 'body'
        ],
        'control' => [
            'relation' => 'stats',
            'column' => 'control'
        ],
        'guard' => [
            'relation' => 'stats',
            'column' => 'guard'
        ],
        'speed' => [
            'relation' => 'stats',
            'column' => 'speed'
        ],
        'stamina' => [
            'relation' => 'stats',
            'column' => 'stamina'
        ],
        'guts' => [
            'relation' => 'stats',
            'column' => 'guts'
        ],
        'freedom' => [
            'relation' => 'stats',
            'column' => 'freedom'
        ],
        'technique' => [
            'relation' => 'techniques',
            'column' => 'name'
        ]
    ];
}
