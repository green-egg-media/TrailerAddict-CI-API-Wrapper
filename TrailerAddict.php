<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 *
 * Green Egg Media TrailerAddict API Wrapper Library
 * for use with CodeIgniter
 * 
 * http://www.greeneggmedia.com/
 *
 * @package 		
 * @category		Library
 * @version 		Version 0.1
 * @author			Adam Fairholm (Green Egg Media)
 * @copyright 		Copyright (c) 2010 Green Egg Media
 * @license 		GNU General Public License v3
 * 
 * Wraps API functions for TrailerAddict.com for use with CI. No API key necessary.
 */

// ------------------------------------------------------------------------

class Traileraddict
{

	/**
	 * Width of the player
	 */
	var $width						= 450;
	
	/**
	 * Number of trailers you want. 1-8.
	 */
	var $count						= 1;
	
	// ------------------------------------------------------------------------

	var $api_url					= 'http://api.traileraddict.com/?';

	var $options					= array('width', 'count');

	// ------------------------------------------------------------------------

	function Traileraddict( $config = array() )
	{
		$this->initialize( $config );
	
	}

	// ------------------------------------------------------------------------
	
	/**
	 * Initialize to class variables
	 */
	function initialize( $config )
	{
		if( !empty($config) ):
		
			foreach( $config as $key => $value ):
		
				$this->$key = $value;
		
			endforeach;
		
		endif;
		
	}

	// ------------------------------------------------------------------------

	/**
	 * Get featured trailers
	 *
	 * @access	public
	 * @param	array
	 * @return	object
	 */
	function get_featured( $config = array() )
	{
		$this->initialize( $config );
	
		$data['featured'] = "yes";
		
		return $this->request( $this->_build_data() );
	}

	// --------------------------------------------------------------------------
	
	/**
	 * Get trailers for a film
	 *
	 * @access	public
	 * @param	string
	 * @param	[array]
	 * @return	object
	 */
	function get_film_trailers( $film_name, $config = array() )
	{
		$this->initialize( $config );
	
		$data['film'] = $this->guess_syntax( $film_name );
		
		return $this->request( $this->_build_data( $data ) );
	}

	// --------------------------------------------------------------------------
	
	/**
	 * Get trailers for an actor
	 *
	 * @access	public
	 * @param	string
	 * @param	[array]
	 * @return	object
	 */
	function get_actor_trailers( $actor_name, $config = array() )
	{
		$this->initialize( $config );
	
		$data['actor'] = $this->guess_syntax( $actor_name );
		
		return $this->request( $this->_build_data( $data ) );
	}

	// --------------------------------------------------------------------------
	
	/**
	 * Use TA's simple API to get data from a single film
	 *
	 * @access	public
	 * @param	string
	 * @return	object
	 */
	function simple_data( $alt_url )
	{
		$request = "http://simpleapi.traileraddict.com/".$alt_url;

		$response = @file_get_contents( $request );

		return simplexml_load_string( $response );
	}

	// ------------------------------------------------------------------------
	
	/**
	 * Make the API request
	 *
	 * @access	public
	 * @param	[array]
	 * @return	object
	 */
	function request( $data = array() )
	{
		$request = $this->api_url . $this->_build_string( $data );
		
		$response = @file_get_contents( $request );

		$xml = simplexml_load_string( $response );

		return $xml;	
	}

	// --------------------------------------------------------------------------

	/**
	 * Build request data array into a string
	 *
	 * @access	private
	 * @param	array data
	 * @return 	string
	 */
	function _build_string( $data )
	{
		$return = NULL;
		$i = 0;
		$t = count( $data );

		foreach( $data as $k => $v ) {

			$k = urlencode( $k );
			$v = urlencode( $v );

			$return .= "&{$k}={$v}";

		}

		return $return;
	}

	// --------------------------------------------------------------------------

	/**
	 * Build basic config data array
	 *
	 * @access	private
	 * @param	[array]
	 * @return 	array
	 */	
	function _build_data( $data = array() )
	{
		$options = array();

		foreach( $this->options as $option )
		{
			$options[$option] = $this->$option;
		}
		
		return array_merge($data, $options);
	}

	// --------------------------------------------------------------------------

	/**
	 * Guess the TA syntax from a regular string
	 *
	 * @access	public
	 * @param	string
	 * @return 	string
	 */	
	function guess_syntax( $string )
	{	
		$string = str_replace(" ", "-", $string);
		
		$string = str_replace(array(".","?","/"), "", $string);
		
		$string = strtolower($string);
		
		return $string;
	}
}

/* End of file TrailerAddict.php */
/* Location: ./application/libraries/TrailerAddict.php */