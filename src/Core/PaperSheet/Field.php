<?php

namespace LJCGrowup\OMR\Core\PaperSheet;

use \ArrayIterator;
use \IteratorAggregate;
use LJCGrowup\OMR\Core\PaperSheet\Mark;

class Field implements IteratorAggregate
{
	private $identifier;
	private $marks = array();

	public function __construct($identifier)
	{
		$this->identifier = $identifier;
	}

	public function addMark(Mark $mark)
	{
		$this->marks[] = $mark;
	}

	public function getIdentifier()
	{
		return $this->identifier;
	}

	public function getMarks()
	{
		return $this->marks;
	}

	public function getIterator(): ArrayIterator
	{
		return new ArrayIterator($this->marks);
	}
}
