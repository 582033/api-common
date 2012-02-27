<?php
function parse_apk_label_icon($manifest) { # {{{
	/**
    manifest - from 'aapt dump badging'

    >>> parse_apk_label_icon(u"""application: label='Star'dunk' icon='res/drawable/icon.png'""")
    (u"Star'dunk", u'res/drawable/icon.png')
    >>> parse_apk_label_icon(u"""application: label='' icon=''""")
    (None, None)
    >>> parse_apk_label_icon(u"""application: label='a
    ... b ' icon='res/drawable/icon.png'""")
    (u'a\\nb', u'res/drawable/icon.png')
    >>> parse_apk_label_icon(u"""application: label='' icon='res/drawable/icon.png'
    ... launchable activity name='com.foo'label='Big bar' icon=''""")
    (u'Big bar', u'res/drawable/icon.png')
	*/

    $label = NULL;
	$icon = NULL;
	preg_match_all("#label='(.*?)' *icon='([^']*)#s", $manifest, $all_matches, PREG_SET_ORDER); # re.dotall
	foreach ($all_matches as $m) {
        $label = $label ? $label : $m[1];
        $icon = $icon ? $icon : $m[2];
	}

    if ($label) {
        $label = trim($label);
	}
	if (!$label) {
		$label = NULL;
	}
	if (!$icon) {
		$icon = NULL;
	}
    return array($label, $icon);
} # }}}

function parse_apk_package_version($manifest) { # {{{
	/**
    manifest - from 'aapt dump badging'

    >>> parse_apk_package_version(u"package: name=' com.foo' versionCode='1530' versionName='1.5.3'")
    (u'com.foo', u'1530', u'1.5.3')
    >>> parse_apk_package_version(u"package: name='com.foo' versionCode='' versionName=''")
    (u'com.foo', u'', u'')
	*/
    if (preg_match("#package: name='(.+)' versionCode='(.*)' versionName='(.*)'#", $manifest, $m)) {
        list($_, $package, $vcode, $vname) = $m;
        $package = trim($package);
	}
    else {
        $package = $vcode = $vname = NULL;
	}

    return array($package, $vcode, $vname);
} # }}}

function parse_aapt_output($manifest) { # {{{
	/**
    >>> parse_aapt_output(u'')
    {'package': None, 'version_name': None, 'icon': None, 'version_code': None, 'supports_screens': None, 'label': None}
	*/
    list($label, $icon) = parse_apk_label_icon($manifest);
    list($package, $version_code, $version_name) = parse_apk_package_version($manifest);
    return array(
            'label' => $label,
            'icon' => $icon,
            'package' => $package,
            'version_code' => $version_code,
            'version_name' => $version_name,
            );
} # }}}
