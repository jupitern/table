<?php
namespace Jupitern\Table;

Class TablePlugin
{
	public $tableInstance;
	public $params;

	public function __construct(Table &$tableInstance)
	{
		$this->tableInstance = $tableInstance;
		$this->params = new Properties();
	}

	public function param($param, $value)
	{
		$this->params->add($param, $value);
		return $this;
	}

	public function params($params)
	{
		$this->params->addAll($params);
		return $this;
	}

	public function add()
	{
		return $this->tableInstance;
	}

	public function render()
	{
		return '';
	}
}