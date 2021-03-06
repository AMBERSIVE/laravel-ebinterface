<?php

namespace AMBERSIVE\Ebinterface\Tests\Unit;

use Config;
use File;

use Ambersive\Ebinterface\Classes\EbInterface;
use Ambersive\Ebinterface\Models\EbInterfaceTax;
use Ambersive\Ebinterface\Models\EbInterfaceInvoiceLines;
use Ambersive\Ebinterface\Models\EbInterfaceTaxSummary;

use AMBERSIVE\Tests\TestCase;

class EbInterfaceTaxSummaryTest extends TestCase
{

    public EbInterfaceInvoiceLines $lines;

    protected function setUp(): void
    {
        parent::setUp();
        $this->lines = new EbInterfaceInvoiceLines();   

        $this->lines->add(null, function($line){
            $line->setQuantity("STK", 10);
            $line->setUnitPrice(100.00);
            $line->setTax(new EbInterfaceTax("S", 20, $line->getLineAmount()));
        });

        $this->lines->add(null, function($line){
            $line->setQuantity("STK", 10);
            $line->setUnitPrice(100.00);
            $line->setTax(new EbInterfaceTax("S", 20, $line->getLineAmount()));
        });

        $this->lines->add(null, function($line){
            $line->setQuantity("STK", 10);
            $line->setUnitPrice(100.00);
            $line->setTax(new EbInterfaceTax("S", 10, $line->getLineAmount()));
        });

    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    /**
     * Test if tax summary needs the lines data
     */
    public function testIfTaxSummaryThrowExeceptionCauseArgumentsMissing():void {

        $this->expectException(\ArgumentCountError::class);       
        $taxSummary = new EbInterfaceTaxSummary();

    }

    /**
     * Test if the tax summary will create an collection of taxes
     */
    public function testIfTaxSummaryToArrayWillReturnTaxSummary(): void {

        $taxSummary = new EbInterfaceTaxSummary($this->lines);

        $result = $taxSummary->toArray();

        $this->assertNotNull($result);
        $this->assertNotEmpty($result);
        $this->assertEquals(2, sizeOf($result));

    }

    /**
     * Test if the xml for the tax summary will be generated correctly
     */
    public function testIfTaxSummaryToXmlGenerateCorrectOutput(): void {

        $taxSummary = new EbInterfaceTaxSummary($this->lines);
        $xml        = $taxSummary->toXml("root");

        $this->assertEquals("<TaxItem><TaxableAmount>2,000.00</TaxableAmount><TaxPercent TaxCategoryCode='S'>20.00</TaxPercent><TaxAmount>400</TaxAmount></TaxItem><TaxItem><TaxableAmount>1,000.00</TaxableAmount><TaxPercent TaxCategoryCode='S'>10.00</TaxPercent><TaxAmount>100</TaxAmount></TaxItem>", $xml);

    }

    public function testIfTaxSummaryToXmlWillHaveWrapperAround(): void {

        $taxSummary = new EbInterfaceTaxSummary($this->lines);
        $xml        = $taxSummary->toXml("Tax");
        $xml2        = $taxSummary->toXml("root");

        $this->assertNotEquals($xml, $xml2);

    }

}