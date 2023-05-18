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

	public function onPlayerDeath(PlayerDeathEvent $event): void {
		if ($this->getConfig()->get("keepInventory")) {
			$worldName = $event->getPlayer()->getWorld()->getDisplayName();
			$worlds = $this->getConfig()->get("worlds");
			$isBlacklist = match (boolval($this->getConfig()->get("mode"))) {
				"blacklist" => true,
				"whitelist" => false
			};
			if ($isBlacklist) {
				if (!in_array($worldName, $worlds)) {
					$event->setKeepInventory(true);
				}
			} else {
				if (in_array($worldName, $worlds)) {
					$event->setKeepInventory(false);
				}
			}
		}
	}
}
