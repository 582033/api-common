<?php
require_once 'PHPUnit/Framework.php';
require_once 'helpers/MY_url_helper.php';

class UrlHelperTest extends PHPUnit_Framework_TestCase {
	public function test_url_append_params() {
		$this->assertEquals('http://foo.com?a=b&c=d',
				url_append_params('http://foo.com', array('a'=>'b','c'=>'d')));
		$this->assertEquals('http://foo.com?a=b&c=d&f=1&x=1',
				url_append_params('http://foo.com?a=x&c=d&f=1', array('a'=>'b', 'x' => 1)));
	}
	public function test_url_remove_params() {
		$this->assertEquals('http://foo.com?c=d',
				url_remove_params('http://foo.com?a=b&c=d', array('a', 'x')));
		$this->assertEquals('http://foo.com',
				url_remove_params('http://foo.com?a=b&c=d', array('a', 'c')));
	}
}
