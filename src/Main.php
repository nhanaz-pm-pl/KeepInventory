<?php

/**
 * Growth plugin for PocketMine-MP
 * Copyright (C) 2021 NhanAZ <https://github.com/NhanAZ>
 *
 * Growth is licensed under the GNU General Public License v3.0 (GPL-3.0 License)
 *
 * GNU General Public License <https://www.gnu.org/licenses/>
 *
 * Discord :: NhanAZ#9115
 * Email   :: NhanAZ@pm.me
 * Twitter :: @ThanhNhanAZ
 */

declare(strict_types=1);

namespace NhanAZ\KeepInventory;

use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\event\player\PlayerDeathEvent;

/**
 * Class Main
 * @package NhanAZ\KeepInventory
 */
class Main extends PluginBase implements Listener
{

	/**
	 * @return void
	 */
	protected function onEnable() : void
	{
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->saveDefaultConfig();
		$notices = $this->getConfig()->get("Notices");
		$keepInventory = $this->getConfig()->get("KeepInventory");
		if ($notices == true) {
			if ($keepInventory == true) {
				$this->getLogger()->notice("Items in the player's inventory that will be kept after they die are enabled.");
				$this->getLogger()->notice("Edit `KeepInventory: true` to `KeepInventory: false` in `config.yml` to disable.");
			} else {
				$this->getLogger()->notice("Items in the player's inventory that will not be kept after they die are enabled.");
				$this->getLogger()->notice("Edit `KeepInventory: false` to `KeepInventory: true` in `config.yml` to enable.");
			}
		} else {
			$this->getLogger()->warning("The notifications related to this plugin are being disabled!");
			$this->getLogger()->warning("Edit `Notices: true` in `config.yml` to enable!");
		}
	}

	/**
	 * @param PlayerDeathEvent $event
	 * @priority HIGHEST
	 */
	public function PlayerDeath(PlayerDeathEvent $event)
	{
		$player = $event->getPlayer();
		$keepInventory = $this->getConfig()->get("KeepInventory");
		$messageType = $this->getConfig()->get("MessageType");
		$messageAfterDeath = $this->getConfig()->get("MessageAfterDeath");
		if ($keepInventory == true) {
			$event->setKeepInventory(true);
			if ($messageType !== "none") {
				if ($messageType == "message") {
					$player->sendMessage($messageAfterDeath);
				}
				if ($messageType == "title") {
					$player->sendTitle($messageAfterDeath);
				}
				if ($messageType == "popup") {
					$player->sendPopup($messageAfterDeath);
				}
				if ($messageType == "tip") {
					$player->sendTip($messageAfterDeath);
				}
				if ($messageType == "actionbarmessage") {
					$player->sendActionBarMessage($messageAfterDeath);
				}
			}
		} else {
			$event->setKeepInventory(false);
		}
	}
}
