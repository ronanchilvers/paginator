<?php

namespace Ronanchilvers\Orm\Paginator;

use Countable;
use ClanCats\Hydrahon\Query\Sql\Select;
use Iterator;

/**
 * Class to manage paginating over a result set
 *
 * @author Ronan Chilvers <ronan@d3r.com>
 */
abstract class AbstractPaginator implements
    Iterator,
    Countable
{
    /**
     * @var ClanCats\Hydrahon\Query\Sql\Select
     */
    protected $select;

    /**
     * @var int
     */
    protected $page;

    /**
     * @var int
     */
    protected $perPage;

    /**
     * @var int
     */
    protected $index;

    /**
     * @var array
     */
    protected $data;

    /**
     * Class constructor
     *
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public function __construct(Select $select, int $page, int $perPage = 10)
    {
        if (($page = (int) $page) < 0) {
            $page = 0;
        }
        if (($perPage = (int) $perPage) < 0) {
            $perPage = 10;
        }
        $this->select = $select;
        $this->page = $page;
        $this->perPage = $perPage;
        $this->data = null;
        $this->index = 0;
    }

    /** START Countable compliance **/

    public function count()
    {
        if (is_null($this->data)) {
            $this->data = $this->load();
        }
        return count($this->data);
    }

    /** END Countable compliance **/

    /** START Iterator compliance **/

    public function current()
    {
        if (is_null($this->data)) {
            $this->data = $this->load();
        }

        return current($this->data);
    }

    public function key()
    {
        if (is_null($this->data)) {
            $this->data = $this->load();
        }

        return key($this->data);
    }

    public function next(): void
    {
        if (is_null($this->data)) {
            $this->data = $this->load();
        }

        next($this->data);
    }

    public function rewind(): void
    {
        if (is_null($this->data)) {
            $this->data = $this->load();
        }

        rewind($this->data);
    }

    public function valid(): bool
    {
        if (is_null($this->data)) {
            $this->data = $this->load();
        }

        return !is_null(key($this->data));
    }

    /** END Iterator compliance **/

    /**
     * Are there more records available
     *
     * @return boolean
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public function hasMore(): bool
    {
        if (is_null($this->data)) {
            $this->data = $this->load();
        }

        return $this->paginatorHasMore();
    }

    /**
     * Are there less records available
     *
     * @return boolean
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public function hasLess()
    {
        if (is_null($this->data)) {
            $this->data = $this->load();
        }

        return 1 < $this->page;
    }

    /**
     * Get the current page number
     *
     * @return int
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    protected function page(): int
    {
        return $this->page;
    }

    /**
     * Get the per page count
     *
     * @return int
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    protected function perPage(): int
    {
        return $this->perPage;
    }

    /**
     * Get the select object
     *
     * return ClanCats\Hydrahon\Query\Sql\Select
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    protected function select(): Select
    {
        return $this->select;
    }

    /**
     * Internal method to detect if there are more records available
     *
     * @return bool
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    abstract protected function paginatorHasMore();

    /**
     * Load the data from the database by executing the select query
     *
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    abstract protected function load();
}
