<?php

function __autoload($class)
{
	$parts = explode('\\', $class);
	require '../src/'.end($parts) . '.php';
}