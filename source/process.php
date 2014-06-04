<?php

include 'div.php';

$length = count($argv);
if ($length == 2){
	print(new div($argv[1], array()));
	}
else{
	
	if (is_dir($argv[2])){
		$basename = basename($argv[1]);
		$dotpos = strrpos($basename, '.');
		if ( $dotpos === False){
			$outfile = join(DIRECTORY_SEPARATOR, array($argv[2], $basename.'.html'));
			}
		else{
			$outfile = join(DIRECTORY_SEPARATOR, array($argv[2], substr($basename, 0, $dotpos).'.html'));
			}
		}
	else{
		$outfile = $argv[2];
		}
	
	if ($length == 3){
		$div = new div($argv[1], array());
		file_put_contents($outfile, "$div");
		}
	elseif ($length == 4) {
		$div = new div($argv[1], file_get_contents($argv[3]));
		file_put_contents($outfile, "$div");
		}
	else {
		//TODO: accept more than 1 data file
		die("Still doesn't works!\n");
		}

	}
