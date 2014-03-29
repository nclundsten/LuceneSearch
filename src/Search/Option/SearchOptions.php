<?php

namespace Search\Option;

class SearchOptions
{
    private $offset;

    protected $query = '';
    protected $limit = 10;
    protected $page = 1;
    protected $sort = false;

    protected $initialized = false;

    public function __construct($opts=array())
    {
        if (is_string($opts)) {
            $this->fromString($opts);
        } elseif (is_array($opts)) {
            $this->fromArray($opts);
        } else {
            throw new \Exception('requires options');
        }
    }

    protected function getOffset()
    {
        return $this->offset;
    }

    public function init($opts)
    {
        if ($this->initialized) {
            throw new \Exception('already initialized!');
        }

        $this->offset = ($this->offset)
            ?: $this->offsetFor($this->limit, $this->page);

        if (isset($opts['newpage'])) {
            $this->changePage($opts['newpage']);
        } elseif (isset($opts['newlimit'])) {
            $this->changeLimit($opts['newlimit']);
        }

        $this->initialized = true;

        return $this;
    }

    public function changePage($page)
    {
        $this->page = $page;
        $this->offset = $this->offsetFor($this->limit, $this->page);
    }

    public function changeLimit($limit)
    {
        $this->limit  = $limit;
        $this->page   = ceil($this->offset/$limit);
        $this->offset = $this->offsetFor($this->limit, $this->page);
    }

    private function offsetFor($limit, $page)
    {
        return (($page * $limit) - $limit) + 1;
    }

    public function query($v=null)
    {
        if ($v !== null) {
            $this->query = $v;
            return $this;
        }
        return $this->query;
    }

    public function limit($v=null)
    {
        if ($v !== null) {
            $this->limit = $v;
            return $this;
        }
        return $this->limit;
    }

    public function page($v=null)
    {
        if ($v !== null) {
            $this->page = $v;
            return $this;
        }
        return $this->page;
    }

    public function sort($v=null)
    {
        if ($v !== null) {
            $this->sort = $v;
            return $this;
        }
        return $this->sort;
    }

    public function fromArray(array $arr)
    {
        foreach ($arr as $k => $v) {
            if (method_exists($this, $k)) {
                $this->$k($v);
            }
        }
        $this->init($arr);
        return $this;
    }

    public function __toString()
    {
        $strs = array();
        foreach (get_object_vars($this) as $k => $v) {
            if ($v == false) continue;
            $strs[] = $k . '=' . $v;
        }
        return implode('&', $strs);
    }

    public function __toArray()
    {
        if (!$this->initialized) {
            throw new \Exception('was not initialized');
        }
        $vars = get_object_vars($this);
        unset($vars['offset']);
        unset($vars['initialized']);
        foreach ($vars as $k => $v) {
            if ($v == false) {
                unset($vars[$v]);
            }
        }
        return $vars;
    }
}

