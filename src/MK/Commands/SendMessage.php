<?php

/*
 * SafeguardAnnouncements v0.0.1 by Safeguard
 * Developer: MK
 * Website: (WIP)
 * Unlicensed - Give me credit :)
 */

namespace MK\Commands;

use pocketmine\command\Command;
use pocketmine\command\CommandExecutor;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\utils\TextFormat;

use MK\SafeguardAnnouncements;

class SendMessage extends PluginCommand implements CommandExecutor {
	/** @var SafeguardAnnouncements */
	private $plugin;

	public function __construct(SafeguardAnnouncements $plugin){
		$this->plugin = $plugin;
	}

	public function onCommand(CommandSender $sender, Command $cmd, string $label, array $args) : bool {
		if($sender->hasPermission("safeguardannouncements.sendmessage")){
			if(isset($args[0]) && isset($args[1])){
				if($args[0] == "*") {
					$this->plugin->announce(SafeguardAnnouncements::TYPE_MESSAGE, $sender->getName(), $this->plugin->getMessagefromArray($args));
				}else if(($player = $this->plugin->getServer()->getPlayerExact($args[0]))){
					$this->plugin->announce(SafeguardAnnouncements::TYPE_MESSAGE, $sender->getName(), $this->plugin->getMessagefromArray($args), $player);
				}else{
					$sender->sendMessage(TextFormat::colorize(SafeguardAnnouncements::PREFIX . "&cPlayer not found"));
				}
			}else{
				$sender->sendMessage(TextFormat::colorize(SafeguardAnnouncements::PREFIX . "&cUsage: /sm <player> <message>"));
			}
		}else{
			$sender->sendMessage(TextFormat::colorize("&cYou don't have permission to use this command."));
		}
		return true;
	}
}