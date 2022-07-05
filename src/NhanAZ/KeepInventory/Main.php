<?php

declare(strict_types=1);

namespace NhanAZ\KeepInventory;

use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use pocketmine\plugin\PluginBase;
use pocketmine\event\player\PlayerDeathEvent;

class Main extends PluginBase {

	protected function onEnable(): void {
		$this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
		$this->saveDefaultConfig();
	}

	private function sendMsgAfterDeath(Player $player, string $msgAfterDeath): void {
		match ($this->getConfig()->get("MsgType", "none")) {
			"message" => $player->sendMessage(TextFormat::colorize($msgAfterDeath)),
			"title" => $player->sendTitle(TextFormat::colorize($msgAfterDeath)),
			"popup" => $player->sendPopup(TextFormat::colorize($msgAfterDeath)),
			"tip" => $player->sendTip(TextFormat::colorize($msgAfterDeath)),
			"actionbar" => $player->sendActionBarMessage(TextFormat::colorize($msgAfterDeath)),
			default => "None"
		};
	}

	private function handleSendMsgAfterDeath(Player $player, bool $sendMessage): void {
		if ($sendMessage) {
			$msgAfterDeath = $this->getConfig()->get("MsgAfterDeathTrue", "You died, but your inventory is safe!");
			$this->sendMsgAfterDeath($player, $msgAfterDeath);
		} else {
			$msgAfterDeath = $this->getConfig()->get("MsgAfterDeathFalse", "You died and your inventory is not safe!");
			$this->sendMsgAfterDeath($player, $msgAfterDeath);
		}
	}

	public function handleKeepInventory(PlayerDeathEvent $event, bool $keepInventory, bool $sendMessage): void {
		$player = $event->getPlayer();
		$worldName = $event->getPlayer()->getWorld()->getDisplayName();
		$worlds = $this->getConfig()->get("Worlds", []);
		switch ($this->getConfig()->get("Mode", "all")) {
			case "all":
				$event->setKeepInventory($keepInventory);
				$this->handleSendMsgAfterDeath($player, $sendMessage);
				break;
			case "whitelist":
				if (in_array($worldName, $worlds, true)) {
					$event->setKeepInventory($keepInventory);
					$this->handleSendMsgAfterDeath($player, $sendMessage);
				} else {
					$event->setKeepInventory(!$keepInventory);
					$this->handleSendMsgAfterDeath($player, !$sendMessage);
				}
				break;
			case "blacklist":
				if (!in_array($worldName, $worlds, true)) {
					$event->setKeepInventory($keepInventory);
					$this->handleSendMsgAfterDeath($player, $sendMessage);
				} else {
					$event->setKeepInventory(!$keepInventory);
					$this->handleSendMsgAfterDeath($player, !$sendMessage);
				}
				break;
		}
	}
}
