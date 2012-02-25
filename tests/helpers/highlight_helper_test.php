<?php
require_once 'PHPUnit/Framework.php';
require_once 'helpers/highlight_helper.php';

class HighlightHelperTest extends PHPUnit_Framework_TestCase {
	public function test_highlight_from_excerpt() {
		$tests = array(
				'q<b>aa</b>m<b>xx</b>q' => '1-3,4-6',
				'<b>aa</b>m<b>xx</b>' => '0-2,3-5',
				'<b>中国</b>m<b>中国</b>' => '0-2,3-5',
				);
		foreach ($tests as $excerpt => $expected) {
			$highlight = highlight_from_excerpt($excerpt);
			$this->assertEquals($expected, $highlight);
		}
	}
}
