<?php

namespace IdeoLearn\Core\Helpers;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Pagination\CursorPaginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Http\Request;

class CollectionHelper
{

    public const DEFAULT_SORT_ATTRIBUTE = 'name';
    public const DEFAULT_SORT_DIRECTION = 'asc';

    /**
     * Get the pagination links based on the pagination type.
     *
     * @param  \Illuminate\Pagination\Paginator  $pagination
     * @param  bool  $useCursor
     * @return array
     */
    public static function getPaginationLinks($pagination, $useCursor)
    {
        if ($useCursor) {
            return [
                'self' => $pagination->toArray()['current_url'],
                'goto' => function ($page) use ($pagination) {
                    return $pagination->toArray()['first_page_url'] . '&cursor=' . $page;
                },
                'first' => $pagination->toArray()['first_page_url'],
                'last' => $pagination->toArray()['last_page_url'],
            ];
        } else {
            return [
                'self' => $pagination->url($pagination->currentPage()),
                'goto' => function ($page) use ($pagination) {
                    return $pagination->url($page);
                },
                'first' => $pagination->url(1),
                'last' => $pagination->url($pagination->lastPage()),
            ];
        }
    }

    /**
     * Get the pagination type based on the presence of the cursor parameter.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    public static function shouldUseCursor(Request $request)
    {
        return $request->input('cursor') !== null;
    }

    /**
     * Paginate the query using the appropriate method.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $perPage
     * @param  int|null  $page
     * @param  string  $pageName
     * @param  bool  $useCursor
     * @return \Illuminate\Pagination\Paginator
     */
    public static function paginate($query, $perPage, $page = null, $pageName = 'page', $useCursor = false): CursorPaginator|LengthAwarePaginator
    {
        if ($useCursor) {
            return $query->cursorPaginate($perPage, ['*'], $pageName, $page);
        } else {
            return $query->paginate($perPage, ['*'], $pageName, $page);
        }
    }

    /**
     * Apply sorting to a query builder.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $sortAttribute
     * @param string $sortDirection
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function applySorting($query, string $sortAttribute = self::DEFAULT_SORT_ATTRIBUTE, string $sortDirection = self::DEFAULT_SORT_DIRECTION)
    {
        $validDirection = in_array(strtolower($sortDirection), ['asc', 'desc']) ? $sortDirection : self::DEFAULT_SORT_DIRECTION;

        return $query->orderBy($sortAttribute, $validDirection);
    }

    /**
     * Transform a paginated collection into a standardized array format.
     *
     * @param ResourceCollection $collection
     * @param Paginator $paginator
     * @param bool $useCursor
     * @param string $sortAttribute
     * @param string $sortDirection
     * @return array
     */
    public static function paginatedResponse(
        ResourceCollection $collection,
        Paginator $paginator,
        bool $useCursor,
        string $sortAttribute = self::DEFAULT_SORT_ATTRIBUTE,
        string $sortDirection = self::DEFAULT_SORT_DIRECTION
    ): array {
        return [
            'items' => $collection->collection,
            '_links' => self::getPaginationLinks($paginator, $useCursor),
            'meta' => self::generateMeta($paginator, $useCursor, $sortAttribute, $sortDirection),
        ];
    }

    /**
     * Generate standardized metadata for pagination.
     *
     * @param Paginator $paginator
     * @param bool $useCursor
     * @param string $sortAttribute
     * @param string $sortDirection
     * @return array
     */
    private static function generateMeta(
        Paginator $paginator,
        bool $useCursor,
        string $sortAttribute,
        string $sortDirection
    ): array {
        return [
            "count" => $paginator->count(),
            "has_more_data" => $paginator->hasMorePages(),
            'current_page' => $useCursor ? null : $paginator->currentPage(),
            'current_page_size' => $paginator->perPage(),
            'total_page_count' => $useCursor ? null : $paginator->lastPage(),
            'total_count' => $paginator->total(),
            'cursor' => $useCursor ? $paginator->nextPageUrl() : null,
            'sort' => [
                [
                    'sort_attr' => $sortAttribute,
                    'sort_dir' => $sortDirection,
                ]
            ]
        ];
    }
}
