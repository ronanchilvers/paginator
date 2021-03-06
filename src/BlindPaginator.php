<?php

namespace Ronanchilvers\Orm\Paginator;

use Ronanchilvers\Orm\Paginator\AbstractPaginator;
use ClanCats\Hydrahon\Query\Sql\Select;

/**
 * A paginator that blindly pages through results
 *
 * This paginator only knows if there is another age or not. It knows nothing about
 * how many pages there might be.
 *
 * @author Ronan Chilvers <ronan@d3r.com>
 */
class BlindPaginator extends AbstractPaginator
{
    /**
     * @var bool
     */
    protected $hasMore = false;

    /**
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    protected function createPages($count = 10): array
    {
        return [];
    }

    /**
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    protected function fetchNextPage(): ?int
    {
        if ($this->hasMore) {
            return $this->page + 1;
        }

        return null;
    }

    /**
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    protected function load(): array
    {
        $select = clone $this->select();
        $page = $this->page;
        $perPage = $this->perPage;

        $offset = ($page - 1) * $perPage;
        $select->limit(
            $offset,
            ($perPage + 1)
        );

        $result = $select->execute();
        if ($this->perPage() < count($result)) {
            $this->hasMore = true;
            array_pop($result);
        }

        return $result;
    }
}
