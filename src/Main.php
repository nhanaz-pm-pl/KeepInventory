<?php

declare(strict_types=1);

namespace NhanAZ\KeepInventory;

use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\event\player\PlayerDeathEvent;

class Main extends PluginBase implements Listener {

	protected function onEnable(): void {
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->saveDefaultConfig();
	}

	public function keepInventory($event) {
		$player = $event->getPlayer();
		$event->setKeepInventory(true);
		$msgAfterDeath = $this->getConfig()->get("MsgAfterDeath");
		match ($this->getConfig()->get("MsgType")) {
			"message" => $player->sendMessage($msgAfterDeath),
			"title" => $player->sendTitle($msgAfterDeath),
			"popup" => $player->sendPopup($msgAfterDeath),
			"tip" => $player->sendTip($msgAfterDeath),
			"actionbar" => $player->sendActionBarMessage($msgAfterDeath),
			default => "None"
		};
	}

	public function PlayerDeath(PlayerDeathEvent $event) {
		if ($this->getConfig()->get("KeepInventory")) {
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
