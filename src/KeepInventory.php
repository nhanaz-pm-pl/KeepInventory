<?php

declare(strict_types=1);

namespace KeepInventory;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use function in_array;

class KeepInventory extends PluginBase implements Listener {
	protected Config $config;

	protected function onEnable() : void {
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->saveDefaultConfig();
		$this->config = $this->getConfig();
	}

	public function keepInventory($event) {
		$player = $event->getPlayer();
		$event->setKeepInventory(true);
		$msgAfterDeath = $this->config->get("MsgAfterDeath", "You died, but your inventory is safe!");
		match ($this->config->get("MsgType", "none")) {
			"message" => $player->sendMessage($msgAfterDeath),
			"title" => $player->sendTitle($msgAfterDeath),
			"popup" => $player->sendPopup($msgAfterDeath),
			"tip" => $player->sendTip($msgAfterDeath),
			"actionbar" => $player->sendActionBarMessage($msgAfterDeath),
			default => "None"
		};
	}

	public function onPlayerDeath(PlayerDeathEvent $event) {
		if ($this->config->get("KeepInventory", true)) {
			$worldName = $event->getPlayer()->getWorld()->getDisplayName();
			$worlds = $this->config->get("Worlds", []);
			switch ($this->config->get("Mode", "all")) {
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
