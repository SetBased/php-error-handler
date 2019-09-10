<?php
declare(strict_types=1);

namespace SetBased\ErrorHandler\Test;

use PHPUnit\Framework\TestCase;
use SetBased\ErrorHandler\ErrorHandler;
use SetBased\Exception\ErrorException;

/**
 * Test cases for class ErrorHandler.
 */
class ErrorHandlerTest extends TestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The error handler.
   *
   * @var ErrorHandler
   */
  private $errorHandler;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Must call the error handler.
   */
  public function __toString(): string
  {
    fopen(__DIR__.'/not-found.txt', 'r');

    return '__toString';
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * An exception handler for testToString2.
   *
   * @param \Exception $exception The exception
   */
  public function exceptionHandler($exception)
  {
    self::assertInstanceOf(ErrorException::class, $exception);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritDoc
   */
  public function setUp(): void
  {
    if ($this->errorHandler===null)
    {
      $this->errorHandler = new ErrorHandler();
    }
    $this->errorHandler->registerErrorHandler();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritDoc
   */
  public function tearDown(): void
  {
    $this->errorHandler->unregisterErrorHandler();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Errors suppressed with the @-operator must not throe exceptions.
   */
  public function testSuppressedError()
  {
    $handle = @fopen(__DIR__.'/not-found.txt', 'r');
    self::assertFalse($handle);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test when __toString fails.
   */
  public function testToString1()
  {
    $tmp = (string)$this;
    self::assertSame('__toString', $tmp);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test when __toString fails.
   */
  public function testToString2()
  {
    set_exception_handler([$this, 'exceptionHandler']);
    $tmp = (string)$this;
    self::assertSame('__toString', $tmp);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test E_WARNING when opening a non existing file throws an exception.
   */
  public function testWarning()
  {
    $this->expectException(ErrorException::class);
    $this->expectExceptionCode(E_WARNING);
    $this->expectExceptionMessageRegExp('/fopen/');
    $this->expectExceptionMessageRegExp(sprintf('!%s!', preg_quote(__DIR__.'/not-found.txt')));
    fopen(__DIR__.'/not-found.txt', 'r');
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
