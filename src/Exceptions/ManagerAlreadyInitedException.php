<?php

namespace Socarrat\Serve\Exceptions;

class ManagerAlreadyInitedException extends \Exception {
    public function __construct(string $managerName, $code = 0, \Throwable $previous = null) {
        parent::__construct($managerName." can only be initialised once.", $code, $previous);
    }
}
