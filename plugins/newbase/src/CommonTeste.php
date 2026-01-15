<?php
// tests/CommonTest.php
class CommonTest {
    public function testFormatCNPJ() {
        $this->assertEquals(
            '12.345.678/0001-90',
            Common::formatCNPJ('12345678000190')
        );
    }

    public function testValidateCNPJ() {
        $this->assertTrue(Common::validateCNPJ('12345678000190'));
        $this->assertFalse(Common::validateCNPJ('11111111111111'));
    }
}
