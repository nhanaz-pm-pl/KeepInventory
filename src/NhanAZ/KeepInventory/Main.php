<?php

declare(strict_types=1);

namespace NhanAZ\KeepInventory;

use pocketmine\plugin\PluginBase;
use pocketmine\event\player\PlayerDeathEvent;

class Main extends PluginBase {

	const CONFIG_VERSION = "1.0.0";

	private function checkConfig(): void {
		$configVersion = $this->getConfig()->exists("ConfigVersion") ? $this->getConfig()->get("ConfigVersion") : "0.0.0";
		if (version_compare($configVersion, self::CONFIG_VERSION, "<>")) {
			$this->getLogger()->warning("Your configuration file is invalid, updating the config.yml...");
			$this->getLogger()->warning("Invalid configuration file can be found at config_invalid.yml");
			rename($this->getDataFolder() . "config.yml", $this->getDataFolder() . "config_invalid.yml");
			$this->saveDefaultConfig();
			$this->getConfig()->reload();
		}
	}

	protected function onEnable(): void {
		$this->checkConfig();
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
