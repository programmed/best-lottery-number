<?php
include 'last_update.php';
date_default_timezone_set("EST");

function validateDate($date) {
	$dt = DateTime::createFromFormat("m/d/y", $date);
	return $dt !== false && !array_sum($dt->getLastErrors());
}

function cleanFile ($file = 'lotto.csv') {

	$reading = fopen("http://flalottery.com/exptkt/ff.htm", 'r');
	$writing = fopen('lotto.csv', 'w');
	$replace = array('JAN', 'FEB', 'MAR', 'APR', 'MAY', 'JUN', 'JUL', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC', 'nbsp');
	$line = 0;
	$comma = 0;
	while (!feof($reading))
	{
		$line = fgetss($reading);
		foreach ($replace as $x)
		{
			if (stristr($line,$x))
			{
				$line = null;
				break;
			}
		}
			$line = preg_replace('/\s+/', '', $line);
		if($line != "" || $line != NULL)
		{
			if (validateDate($line) && $line != 0)
			{
				$line = "\r\n" . $line . ',';
				fputs($writing, $line);
			} else {
				if (stristr($line,'-'))
				{
					$line = ',';
				}
				fputs($writing, $line);
			}
		}
		$line++;
	}
	
	fclose($reading);
	fclose($writing);
}

if (date('d') > $time)
{
	$date = date('d');
	$var = "<?php\n\n\$time = " . $date . ";\n\n?>";
	file_put_contents('last_update.php', $var);
	cleanFile();
}
?>