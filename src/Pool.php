<?php

namespace Chenos\ExecJs;

class Pool
{
    protected $occupiedWorkers = [];

    protected $freeWorkers = [];

    public function get($class)
    {
        if (empty($this->freeWorkers[$class])) {
            $worker = new $class;
        } else {
            $worker = array_pop($this->freeWorkers[$class]);
        }

        $this->occupiedWorkers[$class][spl_object_hash($worker)] = $worker;

        return $worker;
    }

    public function dispose($worker)
    {
        $class = get_class($worker);
        $key = spl_object_hash($worker);

        if (isset($this->occupiedWorkers[$class][$key])) {
            unset($this->occupiedWorkers[$class][$key]);
            $this->freeWorkers[$class][$key] = $worker->cleanup();
        }
    }

    public function count($class)
    {
        $count1 = isset($this->freeWorkers[$class]) ? count($this->freeWorkers[$class]) : 0;
        $count2 = isset($this->occupiedWorkers[$class]) ? count($this->occupiedWorkers[$class]) : 0;

        return $count1 + $count2;
    }
}
