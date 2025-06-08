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
   * @var ErrorHandler|null
   */
  private ?ErrorHandler $errorHandler = null;

  //-------------------------------------------------------------------------------------------------------------------
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
  public function exceptionHandler(\Exception $exception): void
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
   * Errors suppressed with the @-operator must not throw exceptions.
   */
  public function testSuppressedError(): void
  {
    $handle = @fopen(__DIR__.'/not-found.txt', 'r');
    self::assertFalse($handle);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test when __toString fails.
   */
  public function testToString2(): void
  {
    set_exception_handler([$this, 'exceptionHandler']);
    $tmp = (string)$this;
    self::assertSame('__toString', $tmp);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test E_WARNING when opening a non-existing file throws an exception.
   */
  public function testWarning(): void
  {
    $this->expectException(ErrorException::class);
    $this->expectExceptionCode(E_WARNING);
    $this->expectExceptionMessageMatches('/fopen/');
    $this->expectExceptionMessageMatches(sprintf('!%s!', preg_quote(__DIR__.'/not-found.txt')));
    fopen(__DIR__.'/not-found.txt', 'r');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test when __toString fails.
   *
   * The test gives us some problems with PhpUnit 11.
   */
  public function xtestToString1(): void
  {
    $tmp = (string)$this;
    self::assertSame('__toString', $tmp);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
