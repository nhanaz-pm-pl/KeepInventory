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
		$keepInventory = $this->getConfig()->get("KeepInventory");
		$messageType = $this->getConfig()->get("MessageType");
		$messageAfterDeath = $this->getConfig()->get("MessageAfterDeath");
		if ($keepInventory == true) {
			$event->setKeepInventory(true);
			if ($messageType !== "none") {
				if ($messageType == "message") {
					$player->sendMessage($messageAfterDeath);
				}
				if ($messageType == "title") {
					$player->sendTitle($messageAfterDeath);
				}
				if ($messageType == "popup") {
					$player->sendPopup($messageAfterDeath);
				}
				if ($messageType == "tip") {
					$player->sendTip($messageAfterDeath);
				}
				if ($messageType == "actionbarmessage") {
					$player->sendActionBarMessage($messageAfterDeath);
				}
			}
		} else {
			$event->setKeepInventory(false);
		}
	}
}
