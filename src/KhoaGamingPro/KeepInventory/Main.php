<?php

namespace KhoaGamingPro\KeepInventory;

use pocketmine\permission\Permission;
use pocketmine\permission\PermissionManager;
use pocketmine\plugin\PluginBase;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\Listener;
use pocketmine\utils\TextFormat as TF;

class Main extends PluginBase implements Listener {
	
	public function onEnable() : void{
		$this->getServer()->getPluginManager()->registerEvents($this, $this);

		$permission = $this->getConfig()->get("Permission");
		PermissionManager::getInstance()->addPermission(new Permission($permission["Name"], "Permission to keep inventory items on death", $permission["Default"]));
	}

	public function onPlayerDeath(PlayerDeathEvent $ev) : void{
		if($this->getConfig()->get("KeepInventory") === true){
		    $player = $ev->getPlayer();
		    if($player->hasPermission($this->getConfig()->getNested("Permission.Name"))){
				$message = TF::colorize($this->getConfig()->get("MessageAfterDeath"));
				switch($this->getConfig()->get("MessageType")){
					case "chat":
					case "message":
						$player->sendMessage($message);
						break;
					case "popup":
						$player->sendPopup($message);
						break;
					case "tip":
						$player->sendTip($message);
						break;
					case "title":
						$player->sendTip($message);
				}
			}
		}
	}
}
