<?php

namespace GutenPress\Forms;

abstract class Element{

	/**
	 * HTML attributes that apply for any element, according to the HTML5 Draft
	 * @access private
	 */
	private static $global_attributes = array(
		'accesskey',
		'class',
		'contenteditable',
		'contextmenu',
		'dir',
		'draggable',
		'dropzone',
		'hidden',
		'id',
		'lang',
		'spellcheck',
		'style',
		'tabindex',
		'title',
		'translate'
	);

	protected static $element_attributes = array();

	// Element Content
	protected $content;

	// Attributes and such
	protected $properties;

	protected $attributes;

	/**
	 * Build an HTML Element
	 * @param array  $properties Element properties; most likely attributes but might include other stuff to be used by the view
	 * @param string $content Element content
	 */
	public function __construct( array $properties = array() , $content = '' ){
		// recursively filter null values
		$this->properties = \GutenPress\Helpers\Arrays::filterRecursive( $properties );
		$this->content    = $content;

		// collect all attributes properties for this element
		$this->collectAttributes();
	}

	/**
	 * Return the HTML markup for the element.
	 * Must be defined by each extending class.
	 * Use (string)$object to get, or echo $object to output
	 * @return string HTML element
	 */
	abstract public function __toString();

	/**
	 * Loop the element properties and check if they're allowed HTML attributes
	 */
	protected function collectAttributes(){
		foreach ( $this->properties as $key => $val ) {
			if ( in_array($key, self::$global_attributes) || in_array($key, static::$element_attributes) || stripos($key, 'data-') !== false ) {
				$this->attributes[ $key ] = $val;
			}
		}
	}
	public function getAttribute( $attr ){
		return isset($this->attributes[$attr]) ? $this->attributes[$attr] : '';
	}
	public function getAttributes(){
		return $this->attributes;
	}
	public function setAttribute( $attr, $value ){
		$this->attributes[$attr] = $value;
	}

	/**
	 * Get the HTML content for the tag (innerHTML)
	 * @return string
	 */
	public function getContent(){
		return $this->content;
	}

	/**
	 * Get any property from this element
	 * @param string $key The key name for the property
	 * @return mixed The property value (most likely an string)
	 */
	public function getProperty( $key ){
		return isset($this->properties[$key]) ? $this->properties[$key] : '';
	}

	/**
	 * Output all the element's attributes.
	 * @uses esc_attr()
	 * @return string Element attributes
	 */
	public function renderAttributes(){
		$out = '';
		foreach ( $this->attributes as $key => $val ) {
			$out  .= ' '. $key .'="'. esc_attr( $val ) .'"';
		}
		return $out;
	}
}