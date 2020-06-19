<?php
require_once __DIR__ . '/LessThanComparator.php';
require_once __DIR__ . '/EqualComparator.php';
require_once __DIR__ . '/GreaterThanComparator.php';

abstract class Rule {
	protected $comparatorName = array('LessThanComparator', 'EqualComparator', 'GreaterThanComparator');
	
	public abstract function isCompliance($value);
	
}

?>