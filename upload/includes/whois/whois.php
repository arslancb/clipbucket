<?php

function get_preg_expression($mode)
{
	switch ($mode)
	{
		case 'email':
			return '(?:[a-z0-9\'\.\-_\+\|]++|&amp;)+@[a-z0-9\-]+\.(?:[a-z0-9\-]+\.)*[a-z]+';
		break;

		case 'bbcode_htm':
			return array(
				'#<!\-\- e \-\-><a href="mailto:(.*?)">.*?</a><!\-\- e \-\->#',
				'#<!\-\- l \-\-><a (?:class="[\w-]+" )?href="(.*?)(?:(&amp;|\?)sid=[0-9a-f]{32})?">.*?</a><!\-\- l \-\->#',
				'#<!\-\- ([mw]) \-\-><a (?:class="[\w-]+" )?href="(.*?)">.*?</a><!\-\- \1 \-\->#',
				'#<!\-\- s(.*?) \-\-><img src="\{SMILIES_PATH\}\/.*? \/><!\-\- s\1 \-\->#',
				'#<!\-\- .*? \-\->#s',
				'#<.*?>#s',
			);
		break;

		// Whoa these look impressive!
		// The code to generate the following two regular expressions which match valid IPv4/IPv6 addresses
		// can be found in the develop directory
		case 'ipv4':
			return '#^(?:(?:\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.){3}(?:\d{1,2}|1\d\d|2[0-4]\d|25[0-5])$#';
		break;

		case 'ipv6':
			return '#^(?:(?:(?:[\dA-F]{1,4}:){6}(?:[\dA-F]{1,4}:[\dA-F]{1,4}|(?:(?:\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.){3}(?:\d{1,2}|1\d\d|2[0-4]\d|25[0-5])))|(?:::(?:[\dA-F]{1,4}:){5}(?:[\dA-F]{1,4}:[\dA-F]{1,4}|(?:(?:\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.){3}(?:\d{1,2}|1\d\d|2[0-4]\d|25[0-5])))|(?:(?:[\dA-F]{1,4}:):(?:[\dA-F]{1,4}:){4}(?:[\dA-F]{1,4}:[\dA-F]{1,4}|(?:(?:\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.){3}(?:\d{1,2}|1\d\d|2[0-4]\d|25[0-5])))|(?:(?:[\dA-F]{1,4}:){1,2}:(?:[\dA-F]{1,4}:){3}(?:[\dA-F]{1,4}:[\dA-F]{1,4}|(?:(?:\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.){3}(?:\d{1,2}|1\d\d|2[0-4]\d|25[0-5])))|(?:(?:[\dA-F]{1,4}:){1,3}:(?:[\dA-F]{1,4}:){2}(?:[\dA-F]{1,4}:[\dA-F]{1,4}|(?:(?:\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.){3}(?:\d{1,2}|1\d\d|2[0-4]\d|25[0-5])))|(?:(?:[\dA-F]{1,4}:){1,4}:(?:[\dA-F]{1,4}:)(?:[\dA-F]{1,4}:[\dA-F]{1,4}|(?:(?:\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.){3}(?:\d{1,2}|1\d\d|2[0-4]\d|25[0-5])))|(?:(?:[\dA-F]{1,4}:){1,5}:(?:[\dA-F]{1,4}:[\dA-F]{1,4}|(?:(?:\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.){3}(?:\d{1,2}|1\d\d|2[0-4]\d|25[0-5])))|(?:(?:[\dA-F]{1,4}:){1,6}:[\dA-F]{1,4})|(?:(?:[\dA-F]{1,4}:){1,7}:))$#i';
		break;

		case 'url':
		case 'url_inline':
			$inline = ($mode == 'url') ? ')' : '';
			$scheme = ($mode == 'url') ? '[a-z\d+\-.]' : '[a-z\d+]'; // avoid automatic parsing of "word" in "last word.http://..."
			// generated with regex generation file in the develop folder
			return "[a-z]$scheme*:/{2}(?:(?:[a-z0-9\-._~!$&'($inline*+,;=:@|]+|%[\dA-F]{2})+|[0-9.]+|\[[a-z0-9.]+:[a-z0-9.]+:[a-z0-9.:]+\])(?::\d*)?(?:/(?:[a-z0-9\-._~!$&'($inline*+,;=:@|]+|%[\dA-F]{2})*)*(?:\?(?:[a-z0-9\-._~!$&'($inline*+,;=:@/?|]+|%[\dA-F]{2})*)?(?:\#(?:[a-z0-9\-._~!$&'($inline*+,;=:@/?|]+|%[\dA-F]{2})*)?";
		break;

		case 'www_url':
		case 'www_url_inline':
			$inline = ($mode == 'www_url') ? ')' : '';
			return "www\.(?:[a-z0-9\-._~!$&'($inline*+,;=:@|]+|%[\dA-F]{2})+(?::\d*)?(?:/(?:[a-z0-9\-._~!$&'($inline*+,;=:@|]+|%[\dA-F]{2})*)*(?:\?(?:[a-z0-9\-._~!$&'($inline*+,;=:@/?|]+|%[\dA-F]{2})*)?(?:\#(?:[a-z0-9\-._~!$&'($inline*+,;=:@/?|]+|%[\dA-F]{2})*)?";
		break;

		case 'relative_url':
		case 'relative_url_inline':
			$inline = ($mode == 'relative_url') ? ')' : '';
			return "(?:[a-z0-9\-._~!$&'($inline*+,;=:@|]+|%[\dA-F]{2})*(?:/(?:[a-z0-9\-._~!$&'($inline*+,;=:@|]+|%[\dA-F]{2})*)*(?:\?(?:[a-z0-9\-._~!$&'($inline*+,;=:@/?|]+|%[\dA-F]{2})*)?(?:\#(?:[a-z0-9\-._~!$&'($inline*+,;=:@/?|]+|%[\dA-F]{2})*)?";
		break;
	}

	return '';
}

function user_ipwhois($ip)
{
	$ipwhois = '';

	// Check IP
	// Only supporting IPv4 at the moment...
	if (empty($ip) || !preg_match(get_preg_expression('ipv4'), $ip))
	{
		return '';
	}

	if (($fsk = @fsockopen('whois.arin.net', 43)))
	{
		// CRLF as per RFC3912
		fputs($fsk, "$ip\r\n");
		while (!feof($fsk))
		{
			$ipwhois .= fgets($fsk, 1024);
		}
		@fclose($fsk);
	}

	$match = array();

	// Test for referrals from ARIN to other whois databases, roll on rwhois
	if (preg_match('#ReferralServer: whois://(.+)#im', $ipwhois, $match))
	{
		if (strpos($match[1], ':') !== false)
		{
			$pos	= strrpos($match[1], ':');
			$server	= substr($match[1], 0, $pos);
			$port	= (int) substr($match[1], $pos + 1);
			unset($pos);
		}
		else
		{
			$server	= $match[1];
			$port	= 43;
		}

		$buffer = '';

		if (($fsk = @fsockopen($server, $port)))
		{
			fputs($fsk, "$ip\r\n");
			while (!feof($fsk))
			{
				$buffer .= fgets($fsk, 1024);
			}
			@fclose($fsk);
		}

		// Use the result from ARIN if we don't get any result here
		$ipwhois = (empty($buffer)) ? $ipwhois : $buffer;
	}

	return $ipwhois = htmlspecialchars($ipwhois);
}


function cb_who_is_generator($input)
{
	
}