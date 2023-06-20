<?php

namespace RzPack;

class ServiceContainer
{
	protected $services = [];

	public function setService($service_name, $callback)
	{
		$this->services[$service_name] = $callback; 
	}

	public function getService($service_name)
	{
		if (!isset($this->services[$service_name])) {
			throw new \Exception("Not found service << {$service} >>");
		}
		return call_user_func($this->services[$service_name]);
	}
}
