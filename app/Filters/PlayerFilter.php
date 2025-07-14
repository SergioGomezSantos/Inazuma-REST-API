<?php

namespace App\Filters;

use App\Filters\ApiFilter;

class PlayerFilter extends ApiFilter
{

    protected $safeParams = [
        'name' => ['eq'],
        'fullName' => ['eq'],
        'position' => ['eq', 'ne'],
        'element' => ['eq', 'ne'],
        'originalTeam' => ['eq', 'ne'],
        // 'stats' => ['eq'],
        // 'techniques' => ['eq']
    ];

    protected $columnMap = [
        'fullName' => 'full_name',
        'originalTeam' => 'original_team'
    ];

    protected $operatorMap = [
        'eq' => '=',
        'ne' => '!='
    ];
}
