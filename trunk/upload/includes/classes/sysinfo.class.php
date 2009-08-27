<?php
/*******************************************************************************
 *  [ system information ]                                                     *
 *                                                                             *
 *  pedram@redhive.com                                                         *
 *                                                                             *
 *  set of functions that gather and parse system information/statistics.      *
 *******************************************************************************/

/*******************************************************************************
 *  function list                                                              *
 *******************************************************************************
    sys_current_users ()
        return the number of users currently logged in

    sys_net_devices ()
        returns a 2-dimensional array containing device statistics obtained from
        /proc/net/dev. array structure:
            $array[x]["rx_bytes"]     - amount of traffic device has received
            $array[x]["rx_packets"]   - number of packets device has received
            $array[x]["tx_bytes"]     - amount of traffic device has sent
            $array[x]["tx_packets"]   - number of packets device has sent
            $array[x]["rx_per_total"] - % of total traffic device has received
            $array[x]["tx_per_total"] - % of total traffic device has sent
            $array[0]["total_bytes"]  - total amount of traffic seen by all devices
        'x' is the device number, 0 indexed starting from lo. So for example:
            0 = lo, 1 = eth0, 2 = eth1 ... n = eth(n+1)

    sys_memory ()
        return an array containing memory statistics obtained from /proc/meminfo.
        array indexes: (_p represents percent)
                       mem_total        mem_used        mem_free
                       mem_used_p       mem_free_p
                       swap_total       swap_used       swap_used
                       swap_used_p      swap_free_p

    sys_uptime ()
        returns system uptime (xx days, xx hours, xxmins)

    sys_login_stats ()
        returns a 2-dimensional array containing login statistics obtained from
        system wtmp. array structure:
            $array[x]["user"]   -  username
            $array[x]["count"]  -  number of logins since wtmp started
            $array[x]["days"]   -  total number of days logged on
            $array[x]["hours"]  -  total number of hours logged on
            $array[x]["mins"]   -  total number of minutes logged on
            $array[x]["start"]  -  start date of wtmp (Month Year)

 ******************************************************************************/


/*******************************************************************************
 *  configuration                                                              *
 *******************************************************************************/

global $num_devices;    // number of devices on your system including loopback (lo)
$num_devices = 3;



/*******************************************************************************
 *  main functions                                                             *
 *******************************************************************************/


///////////////////////////////////////////////////////////////////////////////
//   sys_current_users
//

function sys_current_users ()    {
    $command = `who | wc -l`;
    return trim($command);
}


///////////////////////////////////////////////////////////////////////////////
//   sys_net_devices
//

function sys_net_devices ()    {
    global $num_devices;
    $proc_net_dev = file("/proc/net/dev");
    $device[$num_devices][17] = array();

    // gather net device information
    for ($i = 0, $index = 0; $proc_net_dev[$i] != NULL; $i++)   {
        if (ereg(":", $proc_net_dev[$i]))   {
            $clean = ereg_replace (' +', ' ', $proc_net_dev[$i]);   // compact whitespace
            $device[$index] = explode (" ", $clean);
            $index++;
        }
    }

    // remove device names
    for ($i = 0; $i < $num_devices; $i++)
        $device[$i][1] = substr($device[$i][1], strpos($device[$i][1], ':') + 1);

    //
    // setup the data structure
    //

    $rb = 1;    // received bytes index
    $rp = 2;    // received packets index
    $sb = 9;    // sent bytes index
    $sp = 10;   // sent packets index

    // store byte/packet count
    for ($i = 0; $i < $num_devices; $i++)   {
        $net_devices[$i]["rx_bytes"]   = convert_bytes($device[$i][$rb]);
        $net_devices[$i]["rx_packets"] = $device[$i][$rp];
        $net_devices[$i]["tx_bytes"]   = convert_bytes($device[$i][$sb]);
        $net_devices[$i]["tx_packets"] = $device[$i][$sp];

        $total_bytes += $device[$i][$rb] + $device[$i][$sb];
    }

	// store interface rx/tx percentage of total
	for ($i = 0; $i < $num_devices; $i++)   {
		if ($total_bytes == 0)
		    $total_bytes = 1;
		$net_devices[$i]["rx_per_total"] = round($device[$i][$rb] / $total_bytes * 100, 2);
		$net_devices[$i]["tx_per_total"] = round($device[$i][$sb] / $total_bytes * 100, 2);
	}

	// store total number of bytes (traffic) seen by all interfaces combined.
	$net_devices[0]["total_bytes"] = convert_bytes($total_bytes);

    // return the data structure
    return $net_devices;
}


///////////////////////////////////////////////////////////////////////////////
//   sys_memory
//

function sys_memory () {
    $proc_meminfo = file("/proc/meminfo");

    // gather system memory information
    for ($i = 0; $proc_meminfo[$i] != NULL; $i++)   {
        if (ereg("Mem:", $proc_meminfo[$i]))    {
            $clean = ereg_replace (' +', ' ', $proc_meminfo[$i]);   // compact whitespace
            $mem   = explode (" ", $clean);
        }
        if (ereg("Swap:", $proc_meminfo[$i]))   {
            $clean = ereg_replace (' +', ' ', $proc_meminfo[$i]);   // compact whitespace
            $swap  = explode (" ", $clean);
        }
    }

    // create the data structure
    // 1 = total, 2 = used, 3 = free, 4 = shared, 5 = buffers, 6 = cached
    $memory["mem_total"]   = convert_bytes($mem[1]);
    $memory["mem_used"]    = convert_bytes($mem[2] - $mem[5] - $mem[6]);
    $memory["mem_free"]    = convert_bytes($mem[1] - ($mem[2] - $mem[5] - $mem[6]));
    $memory["mem_used_p"]  = round((($mem[2] - $mem[5] - $mem[6]) / $mem[1]) * 100, 2);
    $memory["mem_free_p"]  = round(($mem[1] - ($mem[2] - $mem[5] - $mem[6])) / $mem[1] * 100, 2);
    $memory["swap_total"]  = convert_bytes($swap[1]);
    $memory["swap_used"]   = convert_bytes($swap[2]);
    $memory["swap_free"]   = convert_bytes($swap[3]);
    $memory["swap_used_p"] = round(($swap[2] / $swap[1]) * 100, 2);
    $memory["swap_free_p"] = round(($swap[3] / $swap[1]) * 100, 2);

    // return the data structure
    return $memory;
}


///////////////////////////////////////////////////////////////////////////////
//   sys_uptime
//

function sys_uptime ()   {
    $dirty = file("/proc/uptime");          // grab the line
    $ticks = trim(strtok($dirty[0], " "));  // sanitize it (pull out the ticks)

    $mins  = $ticks / 60;                   // total mins
    $hours = $mins  / 60;                   // total hours
    $days  = floor($hours / 24);            // total days
    $hours = floor($hours - ($days * 24));  // hours left
    $mins  = floor($mins  - ($days * 60 * 24) - ($hours * 60)); // mins left

    $uptime .= "$days days, ";              // construct the string
    $uptime .= "$hours hours, ";
    $uptime .= "$mins mins";

    return $uptime;                         // return the string
}


///////////////////////////////////////////////////////////////////////////////
//   sys_login_stats
//

function sys_login_stats ()  {
    // determine wtmp start date and store in RFC 822 format
    $parts = split(' ', str_replace("  ", " ", `last | tail -n1`));
    $wtmp_start = date("F Y", strtotime($parts[3] . " " . $parts[4] . " " . $parts[6]));

    // gather login data from wtmp store in following format: <login count> <username>
    $login_count = `last | grep / | cut -d' ' -f1 | sort | uniq -c`;

    // compact whitespace, remove leading/trailing whitespace
    $login_count = trim(ereg_replace("[[:space:]]+", ' ', $login_count));

    // convert to array, format:
    // odds = login name, evens = login count (2, pedram, 5, shawn, ...)
    $login_count = explode(" ", $login_count);

    // loop through our raw data and store the pertinant stats
    for ($i = 0, $x = 0; $i < sizeof($login_count); $i += 2, $x++)  {
        $days = $hours = $mins = 0;

        // strip out the login duration (not including those currently logged in)
        // XXX - add support for users still logged in.
        $login_time = `last | grep -e ^{$login_count[$i+1]} | grep -v still | cut -d'(' -f2`;
        $login_time = ereg_replace("[[:space:]]+", "", str_replace("\n", "", $login_time));
        $login_time = explode(")", $login_time);

        // determine the login durations
        for ($j = 0; $j < sizeof($login_time); $j++)    {
            $entry = explode(":", $login_time[$j]);
            $days  += substr($entry[0], 0, strpos($entry[0], "+"));
            $hours += substr($entry[0], strpos($entry[0], "+"));
            $mins  += $entry[1];
        }

        // formatting
        $hours += floor($mins  / 60);
        $days  += floor($hours / 24);

        $hours = $hours % 24;
        $mins  = $mins  % 60;

        $login_stats[$x]["user"]  = $login_count[$i+1];
        $login_stats[$x]["count"] = $login_count[$i];
        $login_stats[$x]["days"]  = $days;
        $login_stats[$x]["hours"] = $hours;
        $login_stats[$x]["mins"]  = $mins;
        $login_stats[$x]["start"] = $wtmp_start;
    }

    return $login_stats;
}



/*******************************************************************************
 *  helper functions                                                           *
 *******************************************************************************/


///////////////////////////////////////////////////////////////////////////////
//   convert_bytes
//

function convert_bytes ($bytes) {
    $kbytes = $bytes / 1024;

    if ($kbytes > 1048576)
        $converted  = sprintf("%.2f GB", $kbytes / 1048576);
    else if ($kbytes > 1024)
        $converted  = sprintf("%.2f MB", $kbytes / 1024);
    else
        $converted  = sprintf("%.2f KB", $kbytes);

    return $converted;
}

?>