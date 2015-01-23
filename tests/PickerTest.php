<?php

class PickerTest extends \PHPUnit_Framework_TestCase
{
	protected $picker = null;

	public function setUp()
	{
		$this->picker = new StylePicker();
	}

	public function testKnown()
	{
		$styles = $this->picker->getAttributes();

		$this->assertEquals(11, count($styles));

		foreach ($styles as $key => $val)
		{
			$this->assertTrue(in_array($key, array(
				'color', 'background', 'font-size', 'border-style', 'border-width',
				'border-color', 'border-radius', 'text-shadow', 'box-shadow',
				'padding', 'margin'
			)), $key . 'not within the expected known tests');
		}
	}
}