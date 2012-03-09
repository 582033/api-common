<?php
require_once 'PHPUnit/Framework.php';
require_once 'helpers/MY_string_helper.php';

class StringHelperTest extends PHPUnit_Framework_TestCase {
	public function test_full_trim() {
		$this->assertEquals('abc', full_trim(" \xc2\xa0abc\xc2\xa0"));
	}
}
