<?php
require_once 'PHPUnit/Framework.php';
require_once 'helpers/misc_helper.php';

class MiscHelperTest extends PHPUnit_Framework_TestCase {
	public function test_youku_key() {
		$url = 'http://f.youku.com/abc?K=1';
		$key = get_youku_key($url);
		$this->assertEquals('1', $key);
		$url = replace_youku_key($url, '2');
		$this->assertEquals('http://f.youku.com/abc?K=2', $url);
	}
	public function test_joy_key() {
		$url = 'http://1.1.1.1/1/f.flv';
		$key = get_joy_key($url);
		$this->assertEquals('1', $key);
		$url = replace_joy_key($url, '2');
		$this->assertEquals('http://1.1.1.1/2/f.flv', $url);
	}

	public function test_normalize_size() {
		$this->assertEquals('1KB', normalize_size('1KB'));
		$this->assertEquals('1.0 KB', normalize_size('1024'));
		$this->assertEquals('1.5 MB', normalize_size('1572864'));
	}
	public function test_normalize_url() {
		$this->assertEquals('http://htTp://xx', normalize_url('Http://htTp://xx'));
		$this->assertEquals('http://foo.com/a%20b', normalize_url('http://foo.com/a b'));
	}
	public function test_obscure_name() {
		$this->assertEquals('woody-r2.1.1150-h5c0f4b', obscure_name('woody-r2.1.1150'));
	}
}
