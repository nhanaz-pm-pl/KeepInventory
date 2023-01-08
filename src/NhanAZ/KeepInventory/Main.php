<?php

declare(strict_types=1);

namespace NhanAZ\KeepInventory;

use pocketmine\plugin\PluginBase;
use pocketmine\event\player\PlayerDeathEvent;

class Main extends PluginBase {

	protected function onEnable(): void {
		$this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
		$this->saveDefaultConfig();
	}

	public function handleKeepInventory(PlayerDeathEvent $event, bool $keepInventory): void {
		$worldName = $event->getPlayer()->getWorld()->getDisplayName();
		$worlds = $this->getConfig()->get("Worlds", []);
		switch ($this->getConfig()->get("Mode", "all")) {
			case "all":
				$event->setKeepInventory($keepInventory);
				break;
			case "whitelist":
				if (in_array($worldName, $worlds, true)) {
					$event->setKeepInventory($keepInventory);
				} else {
					$event->setKeepInventory(!$keepInventory);
				}
				break;
			case "blacklist":
				if (!in_array($worldName, $worlds, true)) {
					$event->setKeepInventory($keepInventory);
				} else {
					$event->setKeepInventory(!$keepInventory);
				}
				break;
		}
	}
}
