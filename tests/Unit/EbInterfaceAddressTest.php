<?php

namespace AMBERSIVE\Ebinterface\Tests\Unit;

use Config;
use File;

use Ambersive\Ebinterface\Models\EbInterfaceAddress;

use AMBERSIVE\Tests\TestCase;

class EbInterfaceAddressTest extends TestCase
{

    public EbInterface $interface;

    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }
    
    /**
     * An exception should be thrown
     */
    public function testIfInvoiceAddressWillRequireAValidCountryCode():void {

        $this->expectException(\Illuminate\Validation\ValidationException::class);
        $address = new EbInterfaceAddress("Manuel Pirker-Ihl", "Geylinggasse 15", "Vienna", "1130", "XX");

    }

    /**
     * Test if the country code is valid and is stored in the address
     */
    public function testIfInvoiceAddressWillRequireAValidCountryCodeAndWillNotThrowExceptionIfACorrectOne():void {

        $address = new EbInterfaceAddress("Manuel Pirker-Ihl", "Geylinggasse 15", "Vienna", "1130", "AT");
        $this->assertNotNull($address);
        $this->assertEquals("AT", $address->countryCode);

    }

    /**
     * Transform the Address to an valid XML Object
     */
    public function testIfInvoiceAddressCanConvertToXml(): void {

        $address = new EbInterfaceAddress("Manuel Pirker-Ihl", "Geylinggasse 15", "Vienna", "1130", "AT");
        
        $result = $address->toXml("root");

        $this->assertNotFalse(strpos($result, "<Name>Manuel Pirker-Ihl</Name>"));
        $this->assertNotFalse(strpos($result, "<Street>Geylinggasse 15</Street>"));
        $this->assertNotFalse(strpos($result, "<Town>Vienna</Town>"));
        $this->assertNotFalse(strpos($result, "<ZIP>1130</ZIP>"));
        $this->assertNotFalse(strpos($result, "<Country CountryCode='AT'>AT</Country>"));

    }

}