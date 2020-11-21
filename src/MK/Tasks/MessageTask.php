<?php

/*
 * SafeguardAnnouncements v0.0.1 by Safeguard
 * Developer: MK
 * Website: (WIP)
 * Unlicensed - Give me credit :)
 */

namespace MK\Tasks;

use pocketmine\scheduler\Task;
use pocketmine\utils\TextFormat;

use MK\SafeguardAnnouncements;

class MessageTask extends Task {
	/** @var SafeguardAnnouncements */
	private $plugin;

	/** @var int */
	private $i;

	public function __construct(SafeguardAnnouncements $plugin){
		$this->plugin = $plugin;
		$this->i = 0;
	}

	public function onRun(int $currentTick){
		$messages = $this->plugin->c["message-broadcast"]["messages"];
		back:
		if($this->i < count($messages)){
			$this->plugin->getServer()->broadcastMessage(TextFormat::colorize($this->plugin->messageAnnounceStructure($messages[$this->i])));
			$this->i++;
		}else{
			$this->i = 0;
			goto back;
		}
	}

}