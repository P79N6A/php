<?php
//xdebug_start_trace();
ini_set("xdebug.force_display_errors", 1);
ini_set("xdebug.cli_color", 1);
function a($a, $b = 8) {
	b('1');
}

function b($a) {
	c('2');
	return $a;
}

function c($c) {
	debug_print_backtrace();
}

a(8);

$str = 'abc';
$str1 = substr($str, 0, 2);
$p = xx('a', 'b');

function xx($a, $b){
	$x = array();
	array_push($x, $a);
	print(222);
	array_push($x, $b);
	yy();
	return $x;
}
//xdebug_stop_trace();
function yy(){
	print_r(123);
}

class strings {
    static function fix_strings($a, $b) {
        foreach ($b as $item) {
        }
        var_dump(xdebug_get_declared_vars());
    }
}
strings::fix_strings(array(1,2,3), array(4,5,6));
