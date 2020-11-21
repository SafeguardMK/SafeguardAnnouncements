<?php

declare(strict_types=1);

/*
 * SafeguardAnnouncements v0.0.1 by Safeguard
 * Developer: MK
 * Website: (WIP)
 * Unlicensed - Give me credit :)
 */

namespace MK;

use pocketmine\plugin\PluginBase;
use pocketmine\Player;
use pocketmine\scheduler\TaskHandler;
use pocketmine\utils\TextFormat;

class SafeguardAnnouncements extends PluginBase{

	/** @var string */
	const PREFIX = "&c[&o&fSafeguard&r&c] ";

	const API_VERSION = "1.0.0";

	const TYPE_MESSAGE = 0;

	/** @var TaskHandler */
	private $mtask;

	/** @var array */
	public $c;

	/** @var SafeguardAnnouncements */
	private static $instance = null;

	public function onLoad(){
		if(!self::$instance instanceof SafeguardAnnouncements){
			self::$instance = $this;
		}
	}
	public function onEnable(){
		@mkdir($this->getDataFolder());
		$this->saveDefaultConfig();
		$this->c = $this->getConfig()->getAll();
		$this->getCommand("safeguardannouncements")->setExecutor(new Commands\Commands($this));
		$this->getCommand("sendmessage")->setExecutor(new Commands\SendMessage($this));
		$this->initTasks();
	}

	/**
	 * Replace variables inside a string
	 * @param string $str
	 * @param array $vars
	 * @return string
	 */

	public function replaceVars($str, array $vars){
		foreach($vars as $key => $value){
			$str = str_replace("{" . $key . "}", $value, $str);
		}
		return $str;
	}

	/**
	 * Obtain SafeguardAnnouncements API
	 * @return SafeguardAnnouncements
	 */

	public static function getAPI(){
		return self::$instance;
	}

	/**
	 * Obtain SafeguardAnnouncements version
	 * @return string
	 */

	public function getVersion(){
		return $this->getDescription()->getVersion();
	}

	/**
	 * Obtain SafeguardAnnouncements API version
	 * @return string
	 */

	public function getAPIVersion(){
		return self::API_VERSION;
	}

	/**
	 * Reload SafeguardAnnouncements config
	 */

	public function reload(){
		$this->reloadConfig();
		$this->c = $this->getConfig()->getAll();
		$this->mtask->remove();
		$this->initTasks();
	}

	/**
	 * Commence SafeguardAnnouncements tasks
	 */

	public function initTasks(){
		if(isset($this->c["message-broadcast"]["enabled"])){
			$mtime = intval($this->c["message-broadcast"]["time"]) * 20;
			$this->mtask = $this->getScheduler()->scheduleRepeatingTask(new Tasks\MessageTask($this), $mtime);
		}
}

/**
 * Format SafeguardAnnouncements message
 * @param string $message
 * @return string
 */

public function messageAnnounceStructure($message){
	return $this->replaceVars($message, array(
		//Refers to players
		"MAX" => $this->getServer()->getMaxPlayers(),
		"TOTAL" => count($this->getServer()->getOnlinePlayers()),
		"PREFIX" => $this->c["prefix"],
		"SUFFIX" => $this->c["suffix"],
		"TIME" => date($this->c["datetime-format"])
	));
}

/**
 * Announce message
 * @param int $type
 * @param string $sender
 * @param string $message
 * @param Player $recipient
 */

public function announce(int $type, $sender, $message, Player $recipient = null) {
	switch($type){
		default:
		case self::TYPE_MESSAGE:
			$format = $this->c["message-broadcast"]["command-format"];
			break;
	}
	$array = array(
	"MAX" => $this->getServer()->getMaxPlayers(),
	"TOTAL" => count($this->getServer()->getOnlinePlayers()),
	"PREFIX" => $this->c["prefix"],
	"SUFFIX" => $this->c["suffix"],
	"TIME" => date($this->c["datetime-format"]),
	"SENDER" => $sender,
	"MESSAGE"=> $message
);

if($recipient){
	$array["PLAYER"] = $recipient->getName();
}

$msg = $this->replaceVars($format, $array);
switch($type){
	default:
	case self::TYPE_MESSAGE;
	if($recipient){
		$recipient->sendMessage(TextFormat::colorize($msg));
		return;
	}
	foreach($this->getServer()->getOnlinePlayers() as $player){
		$player->sendMessage(TextFormat::colorize(str_replace("{PLAYER}", $player->getName(), $msg)));
	}
	return;
}

}

/**
 * Merge array elements with a string
 * @param array $array
 * @return string
 */

public function getMessagefromArray($array){
	unset($array[0]);
	return implode( ' ', $array);
}
}
