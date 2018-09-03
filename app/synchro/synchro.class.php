<?php

class Synchro {

    private $project = "/srv/web/designuj.cz/www/dev/tests/synchro/v4/pushdata.php";
    private $key = 0;
    private $shm_key;
    private $pointer;
    private $stack = array();

    public function __construct() {
        date_default_timezone_set("America/New_York");
        $this->shm_key = ftok($this->project, 'P');
        $this->shmAttach();
        if ($this->shmHasVar()) {
            $this->stack = shm_get_var($this->pointer, $this->key);
        }
    }

    public function clear() {
        if (shm_has_var($this->pointer, $this->key)) {
            shm_remove_var($this->pointer, $this->key);
        }
        $this->shmDetach();
    }

    public function push($x, $y) {
        $this->stack[] = array("time" => time(), "x" => $x, "y" => $y);
        shm_put_var($this->pointer, $this->key, $this->stack);
        $this->shmDetach();
        //print_r(shm_get_var($pointer, $key));
    }

    public function pop($k) {
        unset($this->stack[$k]);
        shm_put_var($this->pointer, $this->key, $this->stack);
    }

    public function shmHasVar() {
        if (shm_has_var($this->pointer, $this->key)) {
            return true;
        }

        return false;
    }

    public function shmAttach() {
        $this->pointer = shm_attach($this->shm_key);
    }

    public function shmDetach() {
        shm_detach($this->pointer);
    }

    public function getShmKey() {
        return $this->shm_key;
    }

    public function getPointer() {
        return $this->pointer;
    }

    public function getKey() {
        return $this->key;
    }

    public function getStack() {
        if ($this->shmHasVar()) {
            $this->stack = shm_get_var($this->pointer, $this->key);
        }
        return $this->stack;
    }

}
