<?php

namespace App\Filters;

use Illuminate\Http\Request;

class RelationApiFilter extends ApiFilter
{
    protected $relationMap = [];

    public function transform(Request $request)
    {
        $eloQuery = [];
        $relationFilters = [];

        foreach ($this->safeParams as $param => $operators) {
            $query = $request->query($param);
            if (!isset($query)) {
                continue;
            }

            if (isset($this->relationMap[$param])) {
                $relationInfo = $this->relationMap[$param];
                foreach ($operators as $operator) {
                    if (isset($query[$operator])) {
                        $relationFilters[$relationInfo['relation']][] = [
                            'column' => $relationInfo['column'],
                            'operator' => $this->operatorMap[$operator],
                            'value' => $query[$operator]
                        ];
                    }
                }
            } else {
                $column = $this->columnMap[$param] ?? $param;
                foreach ($operators as $operator) {
                    if (isset($query[$operator])) {
                        $eloQuery[] = [$column, $this->operatorMap[$operator], $query[$operator]];
                    }
                }
            }
        }

        return [
            'where' => $eloQuery,
            'with' => $relationFilters
        ];
    }
}