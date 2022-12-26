<?php declare(strict_types=1);

namespace ClimbingLogbook\Model;

use PHPUnit\Framework\TestCase;
use ValueError;

/**
 * @coversDefaultClass ClimbingLogbook\Model\Entry
 */
class EntryTest extends TestCase
{
    private Entry $entry;

    protected function setUp(): void
    {
        $this->entry = new Entry('date', '7A', 13, 'Boulder', 'Redpoint', 5, 'Overhang', 'Test piece', 'Great!');
    }
    
    /**
     * Valid input leads to successfull creation of a new Entry object
     *
     * @testdox valid input leads to successfull creation of a new Entry object
     * @covers ::__construct, ::setId, ::setDate, ::setGrade, ::setGradeIndex, ::setClimbType
     * @covers ::setAscentType, ::setAttempts, ::setWallType, ::setClimbName, ::setDetails
     * @group model_entry_tests
     * @return void
     */
    public function testConstructor()
    {
        $newEntry = new Entry('date', '7A', 13, 'Boulder', 'Redpoint', 5, 'Overhang', 'Test piece', 'Great!');

        $this->assertInstanceOf(Entry::class, $newEntry);
    }

    /**
     * Invalid grade inputs throw Errors
     *
     * @testdox invalid grade inputs throw Errors
     * @group model_entry_tests
     * @return void
     */
    public function testSetGradeInvalid()
    {
        $this->expectError(ValueError::class);
        $this->expectErrorMessage('Invalid grade!');
        $this->entry->setGrade('');
    }
}
