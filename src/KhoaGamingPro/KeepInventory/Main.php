<?php

namespace KhoaGamingPro\KeepInventory;

use pocketmine\plugin\PluginBase;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\Listener;
use pocketmine\utils\TextFormat as TF;
use pocketmine\utils\Server;
use pocketmine\Player;
use pocketmine\utils\Config;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;

class Main extends PluginBase implements Listener {
	
	public $prefix = TF::GOLD."[".TF::GREEN."Keep".TF::AQUA."Inventory".TF::GOLD."] ".TF::RESET;
	
	public function onEnable(){
		$this->getServer()->getLogger()->info(TF::GOLD."-=-++-=-++-=-++-=-++-=-++-=-++-=-++-=-");
		$this->getServer()->getLogger()->info($this->prefix.TF::GREEN."Plugin enabled!");
		$this->getServer()->getLogger()->info($this->prefix.TF::WHITE."Plugin by".TF::YELLOW." KhoaGamingPro");
		$this->getServer()->getLogger()->info($this->prefix.TF::WHITE."Plugin version: ".TF::AQUA.$this->getDescription()->getVersion());
		$this->getServer()->getLogger()->info(TF::GOLD."-=-++-=-++-=-++-=-++-=-++-=-++-=-++-=-");
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
                $this->saveDefaultConfig();
		if($this->getConfig()->get("KeepInventory") == true){
			$this->getServer()->getLogger()->notice($this->prefix.TF::GREEN."Keep inventory enabled. Player's inventory will keep after death.");
			$this->getServer()->getLogger()->notice($this->prefix.TF::GREEN."You can disable it in config.yml");
		}
		else {
			$this->getServer()->getLogger()->notice($this->prefix.TF::RED."Keep inventory disabled. Player's inventory won't keep after death");
			$this->getServer()->getLogger()->notice($this->prefix.TF::RED."You can enable it in config.yml");
		}
			
	}
    public function onDisable(){
		$this->getServer()->getLogger()->info($this->prefix.TF::RED."Plugin disabled!");
	}
    
	public function translateColors($string){
		$msg = str_replace("&1",TF::DARK_BLUE,$string);
		$msg = str_replace("&2",TF::DARK_GREEN,$msg);
		$msg = str_replace("&3",TF::DARK_AQUA,$msg);
		$msg = str_replace("&4",TF::DARK_RED,$msg);
		$msg = str_replace("&5",TF::DARK_PURPLE,$msg);
		$msg = str_replace("&6",TF::GOLD,$msg);
		$msg = str_replace("&7",TF::GRAY,$msg);
		$msg = str_replace("&8",TF::DARK_GRAY,$msg);
		$msg = str_replace("&9",TF::BLUE,$msg);
		$msg = str_replace("&0",TF::BLACK,$msg);
		$msg = str_replace("&a",TF::GREEN,$msg);
		$msg = str_replace("&b",TF::AQUA,$msg);
		$msg = str_replace("&c",TF::RED,$msg);
		$msg = str_replace("&d",TF::LIGHT_PURPLE,$msg);
		$msg = str_replace("&e",TF::YELLOW,$msg);
		$msg = str_replace("&f",TF::WHITE,$msg);
		$msg = str_replace("&o",TF::ITALIC,$msg);
		$msg = str_replace("&l",TF::BOLD,$msg);
		$msg = str_replace("&r",TF::RESET,$msg);
		return $msg;
	}
	
	public function PlayerDeath(PlayerDeathEvent $ev){
		if($this->getConfig()->get("KeepInventory") == true){
		    $ev->setKeepInventory(true);
			if($this->getConfig()->get("MessageType") == "chat" or "message"){
			    $ev->getPlayer()->sendMessage($this->translateColors($this->getConfig()->get("MessageAfterDeath")));
			}
			if($this->getConfig()->get("MessageType") == "popup"){
			    $ev->getPlayer()->sendPopup($this->translateColors($this->getConfig()->get("MessageAfterDeath")));
			}
			if($this->getConfig()->get("MessageType") == "tip"){
			    $ev->getPlayer()->sendTip($this->translateColors($this->getConfig()->get("MessageAfterDeath")));
			}
			if($this->getConfig()->get("MessageType") == "title"){
			    $ev->getPlayer()->addTitle($this->translateColors($this->getConfig()->get("MessageAfterDeath")));
			}
		}
		else {
			$ev->setKeepInventory(false);
		}
	}
}
