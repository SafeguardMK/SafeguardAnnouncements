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
use pocketmine\command\defaults\ReloadCommand;
use pocketmine\command\PluginCommand;
use pocketmine\plugin\Plugin;
use pocketmine\utils\TextFormat;

use MK\SafeguardAnnouncements;

class Commands extends PluginCommand implements CommandExecutor {

	/** @var SafeguardAnnouncements */
	private $plugin;

	/** @var int */
	private $lstchk = 0;

	public function __construct(SafeguardAnnouncements $plugin){
		$this->plugin = $plugin;
	}

	public function onCommand(CommandSender $sender, Command $cmd, string $label, array $args) : bool {
		if(isset($args[0])){
			$args[0] = strtolower($args[0]);
			switch($args[0]){
				case "info":
					if($sender->hasPermission("safeguardannouncements.info")){
						$sender->sendMessage(TextFormat::colorize(SafeguardAnnouncements::PREFIX . "&aSafeguardAnnouncements &bv". $this->plugin->getDescription()->getVersion() . "&a developed by MK"));
						$sender->sendMessage(TextFormat::colorize(SafeguardAnnouncements::PREFIX . "&aSafeguardAnnouncements &b". $this->plugin->getDescription()->getWebsite()));
						break;
					}
					$sender->sendMessage(TextFormat::colorize("&cYou don't have permission to use this command."));
					break;

				case "help":
					goto help;

				case "reload":
					if($sender->hasPermission("safeguardannouncements.reload")){
						$this->plugin->reload();
						$sender->sendMessage(TextFormat::colorize(SafeguardAnnouncements::PREFIX . "&aConfig reloaded"));
						break;
					}
					$sender->sendMessage(TextFormat::colorize("&cYou don't have permission to use this command."));
					break;

				default:
					if($sender->hasPermission("safeguardannouncements")){
						$sender->sendMessage(TextFormat::colorize(SafeguardAnnouncements::PREFIX . "&csubcommand &b" . $args[0] . "&c not found. Use &b/sa &cto show available commands"));
						break;
					}
					$sender->sendMessage(TextFormat::colorize("&cYou don't have permission to use this command."));
					break;
			}
			return true;
		}
		help:
		if($sender->hasPermission("safeguardannouncements")){
			$sender->sendMessage(TextFormat::colorize("&2- Command List -"));
			$sender->sendMessage(TextFormat::colorize("&b/sa info &2- &bShows info about SafeguardAnnouncements"));
			$sender->sendMessage(TextFormat::colorize("&b/sa reload &2- &9Reloads config"));
			$sender->sendMessage(TextFormat::colorize("&b/sm &2- &bSends message to a player (* for all players)"));
		}else{
			$sender->sendMessage(TextFormat::colorize("&cYou don't have permission to use this command."));
		}
		return true;
}

}
