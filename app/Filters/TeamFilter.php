<?php

namespace App\Filters;

use App\Filters\ApiFilter;

class TeamFilter extends ApiFilter
{

    protected $safeParams = [
        'name' => ['eq'],
        'userId' => ['eq']
    ];

    protected $columnMap = [
        'userId' => 'user_id',
    ];

    protected $operatorMap = [
        'eq' => '='
    ];
}
