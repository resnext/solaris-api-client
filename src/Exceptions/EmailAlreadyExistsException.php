<?php

namespace Solaris\Exceptions;

use Solaris\ServerException;

/**
 * This exception thrown when your trying add customer with already registered email.
 *
 * Class EmailAlreadyExistsException
 * @package Solaris\Exceptions
 */
class EmailAlreadyExistsException extends ServerException
{

}