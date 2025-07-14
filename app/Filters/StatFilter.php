<?php

namespace App\Filters;

use App\Filters\ApiFilter;

class StatFilter extends ApiFilter
{

    protected $safeParams = [
        'playerId' => ['eq'],
        'version' => ['eq', 'ne'],
        'GP' => ['eq', 'lt', 'lte', 'gt', 'gte'],
        'TP' => ['eq', 'lt', 'lte', 'gt', 'gte'],
        'Kick' => ['eq', 'lt', 'lte', 'gt', 'gte'],
        'Body' => ['eq', 'lt', 'lte', 'gt', 'gte'],
        'Control' => ['eq', 'lt', 'lte', 'gt', 'gte'],
        'Guard' => ['eq', 'lt', 'lte', 'gt', 'gte'],
        'Speed' => ['eq', 'lt', 'lte', 'gt', 'gte'],
        'Stamina' => ['eq', 'lt', 'lte', 'gt', 'gte'],
        'Guts' => ['eq', 'lt', 'lte', 'gt', 'gte'],
        'Freedom' => ['eq', 'lt', 'lte', 'gt', 'gte']
    ];

    protected $columnMap = [
        'playerId' => 'player_id',
    ];

    protected $operatorMap = [
        'eq' => '=',
        'lt' => '<',
        'lte' => '<=',
        'gt' => '>',
        'gte' => '>=',
        'ne' => '!='
    ];
}
