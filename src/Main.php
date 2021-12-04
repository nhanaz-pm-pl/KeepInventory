<?php

namespace KhoaGamingPro\KeepInventory;

use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\event\player\PlayerDeathEvent;

class Main extends PluginBase implements Listener
{


	public function onEnable() : void
	{
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->saveDefaultConfig();
		if ($this->getConfig()->get("KeepInventory") == true) {
			$this->getServer()->getLogger()->notice("Keep inventory enabled. Player's inventory will keep after death. You can disable it in config.yml");
		} else {
			$this->getServer()->getLogger()->notice("Keep inventory disabled. Player's inventory won't keep after death. You can enable it in config.yml");
		}
	}

	public function PlayerDeath(PlayerDeathEvent $event)
	{
		$keepInventory = $this->getConfig()->get("KeepInventory");
		$messageType = $this->getConfig()->get("MessageType");
		$messageAfterDeath = $this->getConfig()->get("MessageAfterDeath");
		if ($keepInventory == true) {
			$event->setKeepInventory(true);
			if ($messageType == "message") {
				$event->getPlayer()->sendMessage($messageAfterDeath);
			}
			if ($messageType == "popup") {
				$event->getPlayer()->sendPopup($messageAfterDeath);
			}
			if ($messageType == "tip") {
				$event->getPlayer()->sendTip($messageAfterDeath);
			}
			if ($messageType == "title") {
				$event->getPlayer()->addTitle($messageAfterDeath);
			}
		} else {
			$event->setKeepInventory(false);
		}
	}
}
