<?php
/**
 * @class  memoAdminModel
 * @author CRA (developers@developers.com)
 * admin model class of memo module
 **/

class hgucalendarAdminModel extends hgucalendar {
	function getHgucalendarAdminList($args){
            $output = executeQueryArray('hgucalendar.getHgucalendarAdminList', $args);
			return $output;
		}
}
?>
