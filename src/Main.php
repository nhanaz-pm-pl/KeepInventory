<?php

declare(strict_types=1);

namespace NhanAZ\KeepInventory;

use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\event\player\PlayerDeathEvent;

class Main extends PluginBase implements Listener {

	protected function onEnable() : void {
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->saveDefaultConfig();
	}

	public function PlayerDeath(PlayerDeathEvent $event) {
		$player = $event->getPlayer();
		$messageAfterDeath = $this->getConfig()->get("MessageAfterDeath");
		if ($this->getConfig()->get("KeepInventory") == true) {
			$event->setKeepInventory(true);
			switch ($this->getConfig()->get("MessageType")) {
				case "message":
					$player->sendMessage($messageAfterDeath);
					break;
				case "title":
					$player->sendTitle($messageAfterDeath);
					break;
				case "popup":
					$player->sendPopup($messageAfterDeath);
					break;
				case "tip":
					$player->sendTip($messageAfterDeath);
					break;
				case "actionbarmessage":
					$player->sendActionBarMessage($messageAfterDeath);
					break;
			}
		} else {
			$event->setKeepInventory(false);
		}
	}
}
