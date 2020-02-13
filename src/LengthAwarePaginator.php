<?php

namespace Ronanchilvers\Orm\Paginator;

use Ronanchilvers\Orm\Paginator\AbstractPaginator;
use ClanCats\Hydrahon\Query\Sql\Select;

/**
 * A classic paginator that knows the length of possible results
 *
 * @author Ronan Chilvers <ronan@d3r.com>
 */
class LengthAwarePaginator extends AbstractPaginator
{
    /**
     * @var int
     */
    protected $total;

    /**
     * @var bool
     */
    protected $hasMore = false;

    /**
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    protected function createPages($count = 10): array
    {
        $links = [];
        $pos = $this->page - ($count / 2 - 1);
        if ($pos < 1) {
            $pos = 1;
        }
        $end = $pos + $count;
        $maxPage = ceil($this->total / $this->perPage);
        if ($end > $maxPage) {
            $end = $maxPage;
        }
        while ($pos < $end) {
            $links[] = $pos;
            $pos++;
        }

        return $links;
    }

    /**
     * Are there more records available?
     *
     * @return bool
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    protected function paginatorHasMore(): bool
    {
        return $this->total >= (($this->page * $this->perPage) + $this->perPage);
    }

    /**
     * {@inherit}
     *
     * This paginator needs to:
     *
     *   - Apply a limit to the select object to get the correct page
     *   - Run a separate query to get the total count of results
     *
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    protected function load(): array
    {
        $resultSelect = clone $this->select();
        $countSelect = clone $this->select();

        $page = $this->page;
        $perPage = $this->perPage;

        $offset = ($page - 1) * $perPage;
        $resultSelect->limit(
            $offset,
            $perPage
        );
        $this->total = $countSelect->count();
        $result = $resultSelect->execute();

        return $result;
    }
}
