<?php

declare(strict_types=1);

namespace KeepInventory;

use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\event\player\PlayerDeathEvent;

class KeepInventory extends PluginBase implements Listener {

	protected function onEnable(): void {
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->saveDefaultConfig();
	}

	public function keepInventory($event): void {
		$player = $event->getPlayer();
		$event->setKeepInventory(true);
		$msgAfterDeath = $this->getConfig()->get("MsgAfterDeath", "You died, but your inventory is safe!");
		match ($this->getConfig()->get("MsgType", "none")) {
			"message" => $player->sendMessage($msgAfterDeath),
			"title" => $player->sendTitle($msgAfterDeath),
			"popup" => $player->sendPopup($msgAfterDeath),
			"tip" => $player->sendTip($msgAfterDeath),
			"actionbar" => $player->sendActionBarMessage($msgAfterDeath),
			default => "None"
		};
	}

	public function onPlayerDeath(PlayerDeathEvent $event): void {
		if ($this->getConfig()->get("KeepInventory", true)) {
			$worldName = $event->getPlayer()->getWorld()->getDisplayName();
			$worlds = $this->getConfig()->get("Worlds", []);
			switch ($this->getConfig()->get("Mode", "all")) {
				case "all":
					$this->keepInventory($event);
					break;
				case "whitelist":
					if (in_array($worldName, $worlds, true)) {
						$this->keepInventory($event);
					}
					break;
				case "blacklist":
					if (!in_array($worldName, $worlds, true)) {
						$this->keepInventory($event);
					}
					break;
			}
		} else {
			$event->setKeepInventory(false);
		}
	}
}
