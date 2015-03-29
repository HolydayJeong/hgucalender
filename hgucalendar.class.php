<?php
/**
 * @class hgucalendar
 * @author CRA (developers@developers.com)
 * high class of the memo module
 **/
class hgucalendar extends ModuleObject {
	/**
	 * constructor
	 *
	 * @return void
	 **/
	function hgucalendar() {
	
	}

	/**
	 * Implement if additional tasks are necessary when installing
	 *
	 * @return Object
	 **/
	function moduleInstall() {
		return new Object();
	}

	/**
	 * a method to check if successfully installed
	 *
	 * @return boolean
	 **/
	function checkUpdate() {
		return false;
	}

	/**
	 * Execute update
	 *
	 * @return Object
	 **/
	function moduleUpdate() {
		return new Object(0, 'success_updated');
	}

	/**
	 * Re-generate the cache file
	 *
	 * @return void
	 **/
	function recompileCache() {
	}

}
?>
