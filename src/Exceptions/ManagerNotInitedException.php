<?php

namespace Socarrat\Core\Exceptions;

class ManagerNotInitedException extends \Exception {
    public function __construct(string $managerName, $code = 0, \Throwable $previous = null) {
        parent::__construct($managerName." is not yet initialised.", $code, $previous);
    }
}
