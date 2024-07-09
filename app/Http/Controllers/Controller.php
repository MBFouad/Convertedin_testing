<?php

namespace App\Http\Controllers;

use App\Exceptions\PaginatorWrongPageNumber;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * @param Builder|Model $builder
     * @param Request       $request
     * @param array         $allowedSortBy
     * @param array         $allowedSearchFields
     *
     * @return array
     * @throws PaginatorWrongPageNumber
     */
    protected function getPaginator(Builder $builder, Request $request, array $allowedSortBy, array $allowedSearchFields = []): array
    {
        $pp   = (int)$request->query('pp', 10);
        $sort = $request->query('sort');
        $asc  = (bool)$request->query('asc', false);
        $page = (int)$request->query('page', 1);
        $q    = trim($request->query('q')) ?: null;

        if (isset($sort, $allowedSortBy[$sort])) {
            $builder->orderBy($allowedSortBy[$sort], $asc ? 'asc' : 'desc');
        } else {
            $sort = '';
        }

        if (null !== $q && $q !== '' && \count($allowedSearchFields)) {
            foreach ($allowedSearchFields as $field) {
                $where[] = sprintf("%s LIKE '%%%s%%'", $field, dbEscapeLikeRaw($q));

            }

            $builder->whereRaw('( ' . implode(' OR ', $where) . ' )');
        }
        $paginator = $builder->paginate($pp);

        if (($paginator->lastPage() < $page) && $paginator->total() !== 0) {
            throw new PaginatorWrongPageNumber('');
        }

        return [
            'paginator' => $paginator,
            'params'    => [
                'pp'   => $pp,
                'sort' => $sort,
                'asc'  => $asc,
                'page' => $page,
                'q'    => $q,
            ],
        ];
    }
}
