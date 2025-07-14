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
        'kick' => ['eq', 'lt', 'lte', 'gt', 'gte'],
        'body' => ['eq', 'lt', 'lte', 'gt', 'gte'],
        'control' => ['eq', 'lt', 'lte', 'gt', 'gte'],
        'guard' => ['eq', 'lt', 'lte', 'gt', 'gte'],
        'speed' => ['eq', 'lt', 'lte', 'gt', 'gte'],
        'stamina' => ['eq', 'lt', 'lte', 'gt', 'gte'],
        'guts' => ['eq', 'lt', 'lte', 'gt', 'gte'],
        'freedom' => ['eq', 'lt', 'lte', 'gt', 'gte']
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
