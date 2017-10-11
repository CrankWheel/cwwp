<?php if ( ! defined( 'WPINC' ) ) {
	die;
}


/**
 * Get paged value
 */
if ( ! function_exists('get_page_num')) :

	function get_page_num() {

		if ( get_query_var('paged') ) {
			$paged = intval(get_query_var('paged'));
		} else if ( get_query_var('page') ) {
			$paged = intval(get_query_var('page'));
		} else {
			$paged = 1;
		}

		return $paged;
	}
endif;


/**
 * Get value from array key
 */
if ( ! function_exists('get_key')) :

	function get_key($k, $a = false) {

		global $widget, $nf;
		$val = false;


		/**
		 * Falback for old way of getting things
		 */
		if ( is_array($k) || is_object($k) ) :

			$key   = $a;
			$array = $k;
		else:

			$key   = $k;
			$array = $a;
		endif;


		/**
		 * Get value from froots/widget variable
		 */
		if ( ! $array ) :

			if ( is_array($widget) ) {
				$array = $widget;
			}
			elseif ( is_array($nf)) {
				$array = $nf;
			}
		endif;


		if (!is_array($array) && !is_object($array))
			return false;

		if (is_array($array) && array_key_exists($key, $array))
			$val = $array[$key];

		if (is_object($array) && property_exists($array, $key))
			$val = $array->$key;

		return $val;
	}
endif;




/**
 * Get value from array/object property and echo along with tag and class
 */
if ( ! function_exists('the_key') ) :

	function the_key( $key, $tag = false, $class = false, $array = false ) {

		if ( is_array($array) || is_object($array) ) {
			$val = get_key($key, $array);
		}
		else {
			$val = get_key($key);
		}

		if ( ! $val )
			return;


		if ( ! $tag ) {

			echo $val;
			return;
		}

		$open = '<' . $tag;

		if ( $class ) {
			$open .= ' class="'. $class .'"';
		}

		$open .= '>' . $val;
		$open .=  '</' . $tag . '>';

		echo $open;
	}
endif;



if ( ! function_exists('printaj') ) :

	function printaj( $var ) {
		print_r('<pre>');
		print_r($var);
		print_r('</pre>');
	}

endif;


if ( ! function_exists('dumpaj') ) :

	function dumpaj( $var ) {
		var_dump('<pre>');
		var_dump($var);
		var_dump('</pre>');
	}
endif;