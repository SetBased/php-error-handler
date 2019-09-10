<?php
declare(strict_types=1);

namespace SetBased\ErrorHandler;

use SetBased\Exception\ErrorException;

/**
 * A class for handling PHP runtime errors and warnings in a uniform manner by throwing exceptions.
 */
class ErrorHandler
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * An error handler function that throws exceptions.
   *
   * @param int         $errno   The level of the error raised.
   * @param string      $errstr  The error message.
   * @param string|null $errfile The filename that the error was raised in.
   * @param int|null    $errline The line number the error was raised at.
   *
   * @return bool
   *
   * See http://php.net/manual/en/function.set-error-handler.php.
   *
   * @throws ErrorException
   */
  public function handleError(int $errno, string $errstr, ?string $errfile, ?int $errline): bool
  {
    if (error_reporting()===0)
    {
      // Error was suppressed with the @-operator. Don't throw an exception.
      return false;
    }

    $exception = new ErrorException($errstr, $errno, $errno, $errfile, $errline);

    // In case error appeared in __toString method we can't throw any exception.
    $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
    array_shift($trace);
    foreach ($trace as $frame)
    {
      if ($frame['function']==='__toString')
      {
        $handler = set_exception_handler(null);
        restore_exception_handler();

        if (is_callable($handler))
        {
          $handler($exception);
        }
        else
        {
          error_log((string)$exception);
        }

        // If running unit tests return true.
        if (defined('PHPUNIT_ERROR_HANDLER_TEST_SUITE')) return true;

        exit(-1);
      }
    }

    throw $exception;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Registers this error handler.
   *
   * @param null|int $errorTypes The mask for triggering this error handler. Defaults to E_ALL. Note E_STRICT is part
   *                             of E_ALL since PHP 5.4.0.
   */
  public function registerErrorHandler($errorTypes = E_ALL)
  {
    ini_set('display_errors', '0');
    set_error_handler([$this, 'handleError'], $errorTypes);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Unregisters this error handler by restoring the PHP error and exception handlers.
   */
  public function unregisterErrorHandler()
  {
    restore_error_handler();
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
