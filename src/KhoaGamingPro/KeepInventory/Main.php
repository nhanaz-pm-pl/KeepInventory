<?php

namespace KhoaGamingPro\KeepInventory;

use pocketmine\plugin\PluginBase;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\Listener;
use pocketmine\utils\TextFormat as TF;

class Main extends PluginBase implements Listener {
	
	public function onEnable() : void{
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
	}

	public function onPlayerDeath(PlayerDeathEvent $ev) : void{
		$ev->setKeepInventory($enabled = ($this->getConfig()->get("KeepInventory") === true));
		if($enabled){
		    $player = $ev->getPlayer();
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
