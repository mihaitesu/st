<?php declare(strict_types=1);
/**
 * Timezones.php
 * ----------------------
 * @author     Mihai Teșu
 * @update     Ian 2018
**/



class Timezones {
	
	public function __construct() {}
	
	
	//--- string gmt
	private function format_GMT_offset(int $offset) {
		$hours = intval($offset / 3600);
		$minutes = abs(intval($offset % 3600 / 60));
		return 'GMT' . ($offset ? sprintf('%+03d:%02d', $hours, $minutes) : '');
	}
	
	
	//--- formatare nume timezone
	private function format_timezone_name(string $name) {
		$name = str_replace('/', ', ', $name);
		$name = str_replace('_', ' ', $name);
		$name = str_replace('St ', 'St. ', $name);
		return $name;
	}
	
	
	//--- returneaza timezones
	public function get_list() {
		$regions = array(
			'Africa'     => DateTimeZone::AFRICA,
			'America'    => DateTimeZone::AMERICA,
			'Antarctica' => DateTimeZone::ANTARCTICA,
			'Arctic'     => DateTimeZone::ARCTIC,
			'Asia'       => DateTimeZone::ASIA,
			'Atlantic'   => DateTimeZone::ATLANTIC,
			'Australia'  => DateTimeZone::AUSTRALIA,
			'Europe'     => DateTimeZone::EUROPE,
			'Indian'     => DateTimeZone::INDIAN,
			'Pacific'    => DateTimeZone::PACIFIC
		);
		$timezones = array();
		foreach ($regions as $region => $mask) {
			$zones = DateTimeZone::listIdentifiers($mask);
			foreach($zones as $timezone) {
				$time = new DateTime(NULL, new DateTimeZone($timezone));
				$offset = $time->getOffset();
				$timezones[$region][$timezone] = $this->format_timezone_name(substr($timezone, strlen($region) + 1)).
												' ('.$this->format_GMT_offset($offset).')'.
												' - '.$time->format('g:ia');
			}
		}
		return $timezones;
	}
	
	
	//--- valideaza timezone
	public function valid_timezone($tz) {
		$tz_list = $this->get_list();
		foreach($tz_list as $region => $list) {
			foreach($list as $timezone => $name) {
				if($timezone === $tz) { return true; }
			}
		}
		return false;
	}
	
	
}
