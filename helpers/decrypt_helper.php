<?php
function hex2bin($hex_str) {
	$bin = pack("H*" , $hex_str);  
	return $bin;
}

function endecrypt_xor($value, $key) {
	$vlen = strlen($value);
	$klen = strlen($key);
	for ($i=0; $i<$vlen; $i++) {
		$j = $i % $klen;
		$value[$i] = $value[$i] ^ $key[$j];
	}

	return $value;
}

function encrypt_xor_hex($value, $key) {
	$value_en = endecrypt_xor($value, $key);
	$value_en_hex = strtoupper(bin2hex($value_en));
	return $value_en_hex;
}

function decrypt_xor_hex($value, $key) {
	$bin = hex2bin($value);
	$bin_de = endecrypt_xor($bin, $key);
	return $bin_de;
}

function test_decrypt() {
	$s = "aBc12gh";
	$key ="xyz3";
	$s_en = encrypt_xor_hex($s, $key);
	$s_de = decrypt_xor_hex($s_en, $key);
	echo "$s\n$s_en\n$s_de\n";
}

// test_decrypt();
