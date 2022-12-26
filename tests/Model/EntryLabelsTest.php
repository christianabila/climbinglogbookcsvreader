<?php

namespace ClimbingLogbook\Model;

use PHPUnit\Framework\TestCase;
use ValueError;

/**
 * @coversDefaultClass ClimbingLogbook\Model\EntryLabels
 */
class EntryLabelsTest extends TestCase
{
    /**
     * Negative entry IDs throw a ValueError
     *
     * @group model_entrylabels_tests
     * @covers ::setEntryId
     * @testdox negative entry IDs throw a ValueError
     * @return void
     */
    public function testSetEntryIdNegativeValue()
    {
        $this->expectError(ValueError::class);
        new EntryLabels((-1)*rand(), 1);
    }

    /**
     * Negative label IDs throw a ValueError
     *
     * @group model_entrylabels_tests
     * @covers ::setLabelId
     * @testdox negative label IDs throw a ValueError
     * @return void
     */
    public function testSetLabelIdNegativeValue()
    {
        $this->expectError(ValueError::class);
        new EntryLabels(1, (-1)*rand());
    }

    /**
     * Positive entry IDs and label IDs are saved
     *
     * @group model_entrylabels_tests
     * @covers ::setEntryId, ::getEntryId, ::setLabelId, ::getLabelId
     * @testdox positive entry IDs and label IDs are saved
     * @return void
     */
    public function testSetEntryIdPositiveValue()
    {
        $entryid = rand();
        $labelid = rand();
        $entrylabel = new EntryLabels($entryid, $labelid);

        $this->assertEquals($entryid, $entrylabel->getEntryId());
        $this->assertEquals($labelid, $entrylabel->getLabelId());
    }
}
