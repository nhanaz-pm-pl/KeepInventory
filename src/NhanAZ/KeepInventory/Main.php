<?php

declare(strict_types=1);

namespace NhanAZ\KeepInventory;

use pocketmine\event\EventPriority;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase implements Listener {

	protected function onEnable(): void {
		$pluginMgr = $this->getServer()->getPluginManager();
		$pluginMgr->registerEvents($this, $this);
		$this->saveDefaultConfig();
		try {
			$onDeath = \Closure::fromCallable([$this, "onPlayerDeath"]);
			$pluginMgr->registerEvent(PlayerDeathEvent::class, $onDeath, EventPriority::HIGHEST, $this);
		} catch (\ReflectionException $e) {
			$this->getLogger()->critical($e->getMessage());
			$this->getServer()->getPluginManager()->disablePlugin($this);
		}
	}

	public function onPlayerDeath(PlayerDeathEvent $event): void {
		$worldName = $event->getPlayer()->getWorld()->getDisplayName();
		$worlds = $this->getConfig()->get("worlds");
		$isBlacklist = match (strval($this->getConfig()->get("mode"))) {
			"whitelist" => false,
			default => true
		};
		$shouldKeepInventory = ($isBlacklist && !in_array($worldName, $worlds)) || (!$isBlacklist && in_array($worldName, $worlds));
		$event->setKeepInventory($shouldKeepInventory);
	}
}
