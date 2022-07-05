<?php

declare(strict_types=1);

namespace NhanAZ\KeepInventory;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDeathEvent;

class EventListener implements Listener {

	protected Main $plugin;

	public function __construct(Main $plugin) {
		$this->plugin = $plugin;
	}

	public function onPlayerDeath(PlayerDeathEvent $event): void {
		if ($this->plugin->getConfig()->get("KeepInventory", true)) {
			$this->plugin->handleKeepInventory($event, true, true);
		} else {
			$this->plugin->handleKeepInventory($event, false, false);
		}
	}
}
