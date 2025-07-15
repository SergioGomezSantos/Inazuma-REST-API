<?php

namespace App\Filters;

use App\Filters\RelationApiFilter;

class TeamFilter extends RelationApiFilter
{

    protected $safeParams = [
        'name' => ['eq'],
        'userId' => ['eq'],
        'playerName' => ['eq'],
        'playerFullName' => ['eq'],
    ];

    protected $columnMap = [
        'userId' => 'user_id',
        'playerName' => 'name',
    ];

    protected $operatorMap = [
        'eq' => '='
    ];

    protected $relationMap = [
        'playerName' => [
            'relation' => 'players',
            'column' => 'name'
        ],
        'playerFullName' => [
            'relation' => 'players',
            'column' => 'full_name'
        ],
    ];
}
