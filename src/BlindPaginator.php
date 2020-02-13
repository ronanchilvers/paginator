<?php

namespace Ronanchilvers\Orm\Paginator;

use App\Orm\Paginator\AbstractPaginator;
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
     * Are there more records available?
     *
     * @return bool
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    protected function hasMore(): bool
    {
        return $this->hasMore;
    }

    /**
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    protected function load()
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
