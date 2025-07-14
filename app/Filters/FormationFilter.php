<?php

namespace App\Filters;

use App\Filters\ApiFilter;

class FormationFilter extends ApiFilter
{

    protected $safeParams = [
        'name' => ['eq'],
        'layout' => ['eq']
    ];

    protected $columnMap = [];

    protected $operatorMap = [
        'eq' => '='
    ];
}
