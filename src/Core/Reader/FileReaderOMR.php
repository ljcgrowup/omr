<?php

namespace LJCGrowup\OMR\Core\Reader;

use LJCGrowup\OMR\Core\PaperSheet\PaperSheet;
use LJCGrowup\OMR\Core\layouts\LayoutModelInterface;

/**
 * Responsavel por verificar os gabaristos que estao na pasta
 * e criar um arquivo texto baseado em um objeto de Layout
 * informado como parametro.
 */
class FileReaderOMR
{

	private $readings = array();
	private $filepath;
	private $layoutpath;

	public function __construct(LayoutModelInterface $layout)
	{
		$this->init($layout);
	}
	
	/**
	 * Defines the folder where text files will be generated with the answers obtained from the templates
	 *
	 * @param string $path [Paths to the files that will be generated]
	 *
	 * @return void
	 */
	public function setPathForFiles(string $path)
	{
		if (! is_dir($path)) {
			mkdir($path);
		}

		$this->filepath = $path;
	}

	public function setLayoutPathToRead(string $path)
	{
		$this->layoutpath = $path;
	}

	public function debug($imagePath)
	{
		echo '<pre>';
		$reader = $this->instanceReader($imagePath);
		var_dump($reader->getResults());
		die;
	}

	public function getReadings($json = false)
	{
		if ($json) {
			return json_encode($json);
		}

		return $this->readings;
	}

	// somente caso necessite de um arquivo texto.
	public function processTxt()
	{
		$id = date("d-m-Y_H-i-s");

		if (! str_ends_with($this->filepath, DIRECTORY_SEPARATOR)) {
			$this->filepath .= DIRECTORY_SEPARATOR;
		}

		$path = "{$this->filepath}{$id}.txt";
		echo "<h1 style='color: red;'>" . $_SERVER["DOCUMENT_ROOT"] . "</h1>";

		$file = fopen($path, 'a');
		foreach ($this->readings as $reading) {
			$data = '';
			foreach ($reading as $mark) {
				$data .= $mark['value'];
			}

			if (!empty($data)) {
				$data .= "\n";
				fwrite($file, $data);
			}
		}
		fclose($file);
		return $path;
	}

	public function processReadings($delete = true)
	{
		if (! str_ends_with($this->layoutpath, DIRECTORY_SEPARATOR)) {
			$this->layoutpath .= DIRECTORY_SEPARATOR;
		}

		$directoryFiles = $this->layoutpath;
		$files = get_filenames($directoryFiles);

		foreach ($files as $file) {
			$imagePath = $directoryFiles . DIRECTORY_SEPARATOR . $file;
			$reader = $this->instanceReader($imagePath);
			$this->readings[] = $reader->getResults();
			unset($reader);
		}

		if ($delete) {
			delete_files($directoryFiles);
		}
	}

	private function init($layout)
	{
		$paper = new PaperSheet($layout->getPaperWidth(), $layout->getPaperHeight());
		$paper = $layout->createFieldCode($paper);
		$paper = $layout->createFieldAnswers($paper);

		$this->paper = $paper;
	}

	private function instanceReader($imagePath)
	{
		return new Reader($imagePath, $this->paper, 4);
	}
}
