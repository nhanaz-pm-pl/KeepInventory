<?php

declare(strict_types=1);

namespace NhanAZ\KeepInventory;

use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\event\player\PlayerDeathEvent;

class Main extends PluginBase implements Listener
{

	protected function onEnable(): void
	{
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->saveDefaultConfig();
	}

	public function keepInventory($event) {
		$player = $event->getPlayer();
		$event->setKeepInventory(true);
		$msgAfterDeath = $this->getConfig()->get("MsgAfterDeath");
		switch ($this->getConfig()->get("MsgType")) {
			case "message":
				$player->sendMessage($msgAfterDeath);
				break;
			case "title":
				$player->sendTitle($msgAfterDeath);
				break;
			case "popup":
				$player->sendPopup($msgAfterDeath);
				break;
			case "tip":
				$player->sendTip($msgAfterDeath);
				break;
			case "actionbar":
				$player->sendActionBarMessage($msgAfterDeath);
				break;
		}
	}

	public function PlayerDeath(PlayerDeathEvent $event)
	{
		if ($this->getConfig()->get("KeepInventory") == true) {
			$worldName = $event->getPlayer()->getWorld()->getDisplayName();
			$worlds = $this->getConfig()->get("Worlds");
			switch ($this->getConfig()->get("Mode")) {
				case "all":
					$this->keepInventory($event);
					break;
				case "whitelist":
					if (in_array($worldName, $worlds)) {
						$this->keepInventory($event);
					}
					break;
				case "blacklist":
					if (!in_array($worldName, $worlds)) {
						$this->keepInventory($event);
					}
					break;
			}
		} else {
			$event->setKeepInventory(false);
		}
	}
}
