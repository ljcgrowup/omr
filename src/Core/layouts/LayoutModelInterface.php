<?php

namespace LJCGrowup\OMR\Core\layouts;

use LJCGrowup\OMR\Core\PaperSheet\PaperSheet;

interface LayoutModelInterface
{
	public function createFieldCode(PaperSheet $paper);
	public function createFieldAnswers(PaperSheet $paper);
	public function getPaperWidth();
	public function getPaperHeight();
}