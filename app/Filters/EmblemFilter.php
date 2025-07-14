<?php

namespace App\Filters;

use App\Filters\ApiFilter;

class EmblemFilter extends ApiFilter
{

    protected $safeParams = [
        'name' => ['eq'],
        'version' => ['eq', 'ne']
    ];

    protected $columnMap = [];

    protected $operatorMap = [
        'eq' => '=',
        'ne' => '!='
    ];
}
