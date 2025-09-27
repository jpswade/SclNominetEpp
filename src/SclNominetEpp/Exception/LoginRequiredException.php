<?php

/**
 * Contains the LoginRequire exception class definition.
 */

namespace SclNominetEpp\Exception;

/**
 * Exception to be thrown when the system is required to be logged in to
 * Nominet but currently isn't.
 */
class LoginRequiredException extends \Exception implements
    ExceptionInterface
{
}
