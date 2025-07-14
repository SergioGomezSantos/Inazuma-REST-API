<?php

namespace App\Filters;

use App\Filters\ApiFilter;

class TechniqueFilter extends ApiFilter
{

    protected $safeParams = [
        'name' => ['eq'],
        'element' => ['eq', 'ne'],
        'type' => ['eq', 'ne']
    ];

    protected $columnMap = [];

    protected $operatorMap = [
        'eq' => '=',
        'ne' => '!='
    ];
}
