<?php

namespace eTorn\Controller;

class Logger 
{
	private $error_log;
	private $debug_log;

	public function __construct()
	{
		$this->error_log = (realpath(dirname(__FILE__) . '/../') . '/logs/error.log');
		$this->createFileIfNotExists($this->error_log);
		$this->debug_log = (realpath(dirname(__FILE__) . '/../') . '/logs/debug.log');
		$this->createFileIfNotExists($this->debug_log);
	}

	public function logError($error)
	{
		if (is_object($error)) {
			$error = (array) $error;
		}

		if (is_array($error)) {
			foreach ($error as $err) {
				$this->write($this->error_log, $err);	
			}
		} else {
			$this->write($this->error_log, $error);
		}
	}

	public function logMessage($message)
	{
		if (is_object($message)) {
			$this->write($this->debug_log, json_encode($message));
		} else if (is_array($message)) {
			foreach($message as $msg) {
				$this->write($this->debug_log, $msg);	
			}
		} else {
			$this->write($this->debug_log, $message);
		}
	}

	private function createFileIfNotExists($file)
	{
		if (!file_exists($file)) {
			touch($file);
		}
	}

	private function getLineWithDate($message)
	{
		return ('[' . date("D d M Y - H:i A") . ']: ' . $message);
	}

	private function write($file, $msg)
	{
		$line = $this->getLineWithDate($msg);
		file_put_contents($file, $line . PHP_EOL, FILE_APPEND );
	}

	public static function getInstance() {
		return new self();
	}

	public static function error($error)
    {
        self::getInstance()->logError($error);
    }

    public static function debug($msg)
    {
        self::getInstance()->logMessage($msg);
    }

}