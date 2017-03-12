<?php
namespace BlawoHD;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\Config;
use pocketmine\entity\Effect;
use pocketmine\item\item;
//Events
use pocketmine\event\player\PlayerDropItemEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
class KitPvP extends PluginBase implements Listener {
    public $prefix = "§7[§cKitPvP§7] §f";
//=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=
    public function onEnable() {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $This->getServer()->getLogger()->info ($this->prefix."§aKitPvP enabled!");
		@mkdir($this->getDataFolder());
		@mkdir($this->getDataFolder()."Players");
    }
    public function onDisable() {
        $This->getServer()->getLogger()->info ($this->prefix."§cKitPvP disabled!");
  }
//=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=
	public function onDrop(PlayerDropItemEvent $event) {
        $event->setCancelled(true);
    }
	public function onDeath(PlayerDeathEvent $event){
		$entity = $event->getEntity();
		$cause = $entity->getLastDamageCause();
		$event->setDeathMessage("");
		if ($cause instanceof EntityDamageByEntityEvent) {
            $killer = $cause->getDamager();
			if($killer instanceof Player){
				$name = $killer->getName();
				$TargetFile = new Config($this->getDataFolder()."Players/".strtolower($name{0})."/".strtolower($name).".yml", Config::YAML);	
				$targetcoins = $TargetFile->get("Coins");
				$newCoins = $targetcoins + 10;
				$addedcoins = 10;
				if($killer->hasPermission("kitpvp.doublecoins") || $killer->isOP()){
					$newCoins = $newCoins + 5;
					$addedcoins = 10;
				}
				$TargetFile->set("Coins", $newCoins);
				$TargetFile->save();
				$Killer->sendMessage ($this->prefix."§aYou have the player §b". $Entity->getName ()."§ kills. §f->§6 +". $Addedcoins."Coins") ;
      }
		}
	}
	
	public function onJoin(PlayerJoinEvent $event) {
		$player = $event->getPlayer();
		$name = $player->getName();
		
		@mkdir($this->getDataFolder()."Players/".strtolower($name{0}));
		
		$PlayerFile = new Config($this->getDataFolder()."Players/".strtolower($name{0})."/".strtolower($name).".yml", Config::YAML);
		
		if(empty($PlayerFile->get("Coins"))){
			$PlayerFile->set("Coins", 0);
		}
		if(empty($PlayerFile->get("Kits"))){
			$PlayerFile->set("Kits", array("Viking"));
		}
		
		$PlayerFile->save();
    }
	
//=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=
	
    public function onCommand(CommandSender $sender, Command $cmd, $lable, array $args) {
		
		$name = $sender->getName();
		$PlayerFile = new Config($this->getDataFolder()."Players/".strtolower($name{0})."/".strtolower($name).".yml", Config::YAML);
		
		$kits = $PlayerFile->get("Kits");
		$coins = $PlayerFile->get("Coins");
		
        switch ($cmd->getName()) {
            case "kits":
				
				$sender->sendMessage("§7=_=_=_=_=_=_=_=_=_");
				$sender->sendMessage(" §7- §8Vikings §7[§aPurchase§7]");
				
				if(in_array("Ninja", $kits)){
					$sender->sendMessage(" §7- §bNinja §7[§aPurchase§7]");
				} else {
					$sender->sendMessage(" §7- §bNinja §7[§c150 coins§7]");
				}
				if(in_array("Mario", $kits)){
					$sender->sendMessage(" §7- §cMario §7[§aPurchase§7]");
				} else {
					$sender->sendMessage(" §7- §cMario §7[§c200 coins§7]");
				}
				if(in_array("Archer", $kits)){
					$sender->sendMessage(" §7- §aArcher §7[§aPurchase§7]");
				} else {
					$sender->sendMessage(" §7- §aArcher §7[§c360 coins§7]");
				}
				if(in_array("Warrior", $kits)){
					$sender->sendMessage(" §7- §4Warrior §7[§aPurchase§7]");
				} else {
					$sender->sendMessage(" §7- §4Warrior §7[§c500 coins§7]");
				}
				if(in_array("Turbo", $kits)){
					$sender->sendMessage(" §7- §fTurbo §7[§aPurchase§7]");
				} else {
					$sender->sendMessage(" §7- §fTurbo §7[§c750 coins§7]");
				}
				if(in_array("King", $kits)){
					$sender->sendMessage(" §7- §6King §7[§aPurchase§7]");
				} else {
					$sender->sendMessage(" §7- §6King §7[§c800 coins§7]");
				}
				if(in_array("Knight", $kits)){
					$sender->sendMessage(" §7- §7Knight §7[§aPurchase§7]");
				} else {
					$sender->sendMessage(" §7- §7Knight §7[§c1000 coins§7]");
				}
				$sender->sendMessage("                     ");
				$sender->sendMessage("§9Kit select§7:   ");
				$sender->sendMessage("§c/kit <KitName>    ");
				$sender->sendMessage("§7=_=_=_=_=_=_=_=_=_");
				
                break;
            case "coins":
				$sender->sendMessage($this->prefix."You have §6".$coins." §fCoins!");
				break;
            case "setcoins":
				if($sender->isOP()){
					if(!empty($args[0]) && !empty($args[1])){
						
						$targetname = $args[0];
						if(file_exists($this->getDataFolder()."Players/".strtolower($targetname{0})."/".strtolower($targetname).".yml")){
							$TargetFile = new Config($this->getDataFolder()."Players/".strtolower($targetname{0})."/".strtolower($targetname).".yml", Config::YAML);
							
							$TargetFile->set("Coins", (int) $args[1]);
							$TargetFile->save();
							
							$sender->sendMessage($this->prefix."You have the coins of §6".$targetname." §fon §6".$args[1]." §fset!");
						} else {
							$sender->sendMessage("Player does not exist!");
						}
						
					} else {
						$sender->sendMessage("/setcoins <player> <amount>");
					}
				}
				break;
            case "addcoins":
				if($sender->isOP()){
					if(!empty($args[0]) && !empty($args[1])){
						
						$targetname = $args[0];
						if(file_exists($this->getDataFolder()."Players/".strtolower($targetname{0})."/".strtolower($targetname).".yml")){
							$TargetFile = new Config($this->getDataFolder()."Players/".strtolower($targetname{0})."/".strtolower($targetname).".yml", Config::YAML);
							
							$targetcoins = $TargetFile->get("Coins");
							$newCoins = $targetcoins + (int) $args[1];
							
							$TargetFile->set("Coins", (int) $newCoins);
							$TargetFile->save();
							
							$sender->sendMessage($this->prefix."You have the coins of §6".$targetname." §fabout §6".$args[1]." §felevated!");
						} else {
							$sender->sendMessage("Player does not exist!");
						}
						
					} else {
						$sender->sendMessage("/addcoins <player> <amount>");
					}
				}
				break;
            case "kit":
				if(!empty($args[0])){
					if (strtolower($args[0]) != "viking" &&
							strtolower($args[0]) != "ninja" &&
							strtolower($args[0]) != "mario" &&
							strtolower($args[0]) != "archer" &&
							strtolower($args[0]) != "krieger" &&
							strtolower($args[0]) != "turbo" &&
							strtolower($args[0]) != "king" &&
							strtolower($args[0]) != "knight") {
						$sender->sendMessage($this->prefix . "§cThe kit §e$args[0] §cdoes not exist or there is a spelling error.");
						$sender->sendMessage("§6-> §f/kits");
					} else {
						###VIKING###
						if (strtolower($args[0] == "viking")) {
							if($sender instanceof Player){
								$sender->removeAllEffects();
								$sender->getInventory()->clearAll();
								$sender->sendMessage($this->prefix . "§fKit §o§l§8Viking §r§frecieved");
								$sender->getInventory()->setHelmet(Item::get(306, 0, 1));
								$sender->getInventory()->setChestplate(Item::get(299, 0, 1));
								$sender->getInventory()->setLeggings(Item::get(300, 0, 1));
								$sender->getInventory()->setBoots(Item::get(301, 0, 1));
								$sender->getInventory()->addItem(Item::get(322, 0, 2));
								$sender->getInventory()->addItem(Item::get(350, 0, 20));
								$sender->getInventory()->addItem(Item::get(258, 0, 1));
								$sender->addEffect(Effect::getEffect(5)->setAmplifier(0)->setDuration(199980)->setVisible(false));
							} else {
								$sender->sendMessage($this->prefix . "§fKit only available ingame:D");
							}
						}
						###NINJA###
						elseif (strtolower($args[0]) == "ninja") {
							
							if(!in_array("Ninja", $kits)){
								
								if($coins >= 150){
									
									$newCoins = $coins - 150;
									
									$kits[] = "Ninja";
									$PlayerFile->set("Kits", $kits);
									$PlayerFile->set("Coins", $newCoins);
									
									$PlayerFile->save();
									
									$sender->sendMessage($this->prefix."§aYou have successfully purchased the kit §bNinja §afor§6 150 coins, you can now use it at any time with the command §f/kit ninja §ause!");
									
								} else {
									$sender->sendMessage($this->prefix."§cYou do not have enough coins to buy the kit §bNinja");
									
									$missingcoins = 150 - $coins;
									
									$sender->sendMessage($this->prefix."Available Coins§7: §6".$coins);
									$sender->sendMessage($this->prefix."Missing Coins§7: §6".$missingcoins);
									$sender->sendMessage($this->prefix."Required Coins§7:§6 150");
								}
								
							} else { //gekauft
								if($sender instanceof Player){
									$sender->removeAllEffects();
									$sender->getInventory()->clearAll();
									$sender->sendMessage($this->prefix . "§fKit §o§l§bNinja §r§frecieved");
									$sender->getInventory()->addItem(Item::get(276, 1010, 1));
									$sender->getInventory()->addItem(Item::get(322, 0, 2));
									$sender->getInventory()->addItem(Item::get(260, 0, 20));
									$sender->getInventory()->setHelmet(Item::get(298, 0, 1));
									$sender->getInventory()->setChestplate(Item::get(299, 0, 1));
									$sender->getInventory()->setLeggings(Item::get(300, 0, 1));
									$sender->getInventory()->setBoots(Item::get(301, 0, 1));
									$sender->addEffect(Effect::getEffect(1)->setAmplifier(1)->setDuration(199980)->setVisible(false));
								} else {
									$sender->sendMessage($this->prefix . "§fKit is only available ingame :D");
								}
							}
						}
						###KING###
						elseif (strtolower($args[0]) == "mario") {
							
							if(!in_array("Mario", $kits)){
								
								if($coins >= 200){
									
									$newCoins = $coins - 200;
									
									$kits[] = "Mario";
									$PlayerFile->set("Kits", $kits);
									$PlayerFile->set("Coins", $newCoins);
									
									$PlayerFile->save();
									
									$sender->sendMessage($this->prefix."§aYou have successfully purchased the kit §cMario §afor§6 200 coins, you can use it at any time with the command §f/kit mario §ause!");
									
								} else {
									$sender->sendMessage($this->prefix."§cYou do not have enough coins to buy the Kit §cMario");
									
									$missingcoins = 200 - $coins;
									
									$sender->sendMessage($this->prefix."Available Coins§7: §6".$coins);
									$sender->sendMessage($this->prefix."Missing Coins§7: §6".$missingcoins);
									$sender->sendMessage($this->prefix."Required Coins§7:§6 200");
								}
								
							} else { //gekauft
								if($sender instanceof Player){
									$sender->removeAllEffects();
									$sender->getInventory()->clearAll();
									$sender->sendMessage($this->prefix . "§fKit §o§l§cMario §r§frecieved");
									$sender->getInventory()->addItem(Item::get(268, 10, 1));
									$sender->getInventory()->addItem(Item::get(282, 0, 4));
									$sender->getInventory()->addItem(Item::get(40, 0, 15));
									$sender->getInventory()->addItem(Item::get(39, 0, 15));
									$sender->getInventory()->addItem(Item::get(322, 0, 2));
									$sender->getInventory()->setHelmet(Item::get(298, 0, 1));
									$sender->getInventory()->setChestplate(Item::get(299, 0, 1));
									$sender->getInventory()->setLeggings(Item::get(300, 0, 1));
									$sender->getInventory()->setBoots(Item::get(301, 0, 1));
									$sender->addEffect(Effect::getEffect(8)->setAmplifier(3)->setDuration(199980)->setVisible(false));
								} else {
									$sender->sendMessage($this->prefix . "§fKit only available ingame :D");
								}
							}
						}
						###ARCHER###
						elseif (strtolower($args[0]) == "archer") {
							
							if(!in_array("Archer", $kits)){
								
								if($coins >= 360){
									
									$newCoins = $coins - 360;
									
									$kits[] = "Archer";
									$PlayerFile->set("Kits", $kits);
									$PlayerFile->set("Coins", $newCoins);
									
									$PlayerFile->save();
									
									$sender->sendMessage($this->prefix."§aYou have sucessfully purchased the Kit §aArcher §afor§6 360 coins, you can now use it at any time with the Command §f/kit archer §ause!");
									
								} else {
									$sender->sendMessage($this->prefix."§cYou do not have enough coins to buy the kit §aArcher");
									
									$missingcoins = 360 - $coins;
									
									$sender->sendMessage($this->prefix."Available Coins§7: §6".$coins);
									$sender->sendMessage($this->prefix."Missing Coins§7: §6".$missingcoins);
									$sender->sendMessage($this->prefix."Required Coins§7:§6 360");
								}
								
							} else { //gekauft
								if($sender instanceof Player){
									$sender->removeAllEffects();
									$sender->getInventory()->clearAll();
									$sender->sendMessage($this->prefix . "§fKit §o§l§aArcher §r§fuse");
									$sender->getInventory()->addItem(Item::get(267, 195, 1));
									$sender->getInventory()->addItem(Item::get(261, 0, 1));
									$sender->getInventory()->addItem(Item::get(262, 0, 40));
									$sender->getInventory()->addItem(Item::get(260, 0, 20));
									$sender->getInventory()->addItem(Item::get(322, 0, 2));
									$sender->getInventory()->setChestplate(Item::get(299, 0, 1));
									$sender->getInventory()->setLeggings(Item::get(300, 0, 1));
									$sender->addEffect(Effect::getEffect(11)->setAmplifier(0)->setDuration(199980)->setVisible(false));
								} else {
									$sender->sendMessage($this->prefix . "§fKit only available ingame :D");
								}
							}
						}
						###Warrior###
						elseif (strtolower($args[0]) == "warrior") {
							
							if(!in_array("Warrior", $kits)){
								
								if($coins >= 500){
									
									$newCoins = $coins - 500;
									
									$kits[] = "Warrior";
									$PlayerFile->set("Kits", $kits);
									$PlayerFile->set("Coins", $newCoins);
									
									$PlayerFile->save();
									
									$sender->sendMessage($this->prefix."§aYou have sucessfully purchased the kit §4Warrior §afor§6 500 coins, you can use it any time with the Command §f/kit warrior §ause!");
									
								} else {
									$sender->sendMessage($this->prefix."§cYou do not have enough coins to buy the kit §4Krieger");
									
									$missingcoins = 500 - $coins;
									
									$sender->sendMessage($this->prefix."Available Coins§7: §6".$coins);
									$sender->sendMessage($this->prefix."Missing Coins§7: §6".$missingcoins);
									$sender->sendMessage($this->prefix."Required Coins§7:§6 500");
								}
								
							} else { //gekauft
								if($sender instanceof Player){
									$sender->removeAllEffects();
									$sender->getInventory()->clearAll();
									$sender->sendMessage($this->prefix . "§fKit §o§l§4Warrior §r§frecieved");
									$sender->getInventory()->addItem(Item::get(272, 0, 1));
									$sender->getInventory()->addItem(Item::get(322, 0, 5));
									$sender->getInventory()->addItem(Item::get(393, 0, 20));
									$sender->getInventory()->setHelmet(Item::get(302, 0, 1));
									$sender->getInventory()->setChestplate(Item::get(307, 0, 1));
									$sender->getInventory()->setLeggings(Item::get(304, 0, 1));
									$sender->getInventory()->setBoots(Item::get(305, 0, 1));
									$sender->addEffect(Effect::getEffect(5)->setAmplifier(0)->setDuration(199980)->setVisible(false));
									$sender->addEffect(Effect::getEffect(11)->setAmplifier(0)->setDuration(199980)->setVisible(false));
								} else {
									$sender->sendMessage($this->prefix . "§fKit only available in game:D");
								}
							}
						}
						###TURBO###
						elseif (strtolower($args[0]) == "turbo") {
							
							if(!in_array("Turbo", $kits)){
								
								if($coins >= 750){
									
									$newCoins = $coins - 750;
									
									$kits[] = "Turbo";
									$PlayerFile->set("Kits", $kits);
									$PlayerFile->set("Coins", $newCoins);
									
									$PlayerFile->save();
									
									$sender->sendMessage($this->prefix."§aYou have sucessfully purchased the kit §fTurbo §afir§6 750 coins, you can use it anytime with the Command §f/kit turbo §ause!");
									
								} else {
									$sender->sendMessage($this->prefix."§cYou do not have enough coins to purchase the kit §fTurbo");
									
									$missingcoins = 750 - $coins;
									
									$sender->sendMessage($this->prefix."Available Coins§7: §6".$coins);
									$sender->sendMessage($this->prefix."Missing Coins§7: §6".$missingcoins);
									$sender->sendMessage($this->prefix."Required Coins§7:§6 750");
								}
								
							} else { //gekauft
								if($sender instanceof Player){
									$sender->removeAllEffects();
									$sender->getInventory()->clearAll();
									$sender->sendMessage($this->prefix . "§fKit §o§l§4Turbo §r§frecieved");
									$sender->getInventory()->addItem(Item::get(272, 0, 1));
									$sender->getInventory()->addItem(Item::get(322, 0, 5));
									$sender->getInventory()->addItem(Item::get(393, 0, 20));
									$sender->getInventory()->setHelmet(Item::get(302, 0, 1));
									$sender->getInventory()->setChestplate(Item::get(307, 0, 1));
									$sender->getInventory()->setLeggings(Item::get(304, 0, 1));
									$sender->getInventory()->setBoots(Item::get(305, 0, 1));
									$sender->addEffect(Effect::getEffect(5)->setAmplifier(0)->setDuration(199980)->setVisible(false));
									$sender->addEffect(Effect::getEffect(11)->setAmplifier(0)->setDuration(199980)->setVisible(false));
								} else {
									$sender->sendMessage($this->prefix . "§fKit only available ingame :D");
								}
							}
						}
						###KING###
						elseif (strtolower($args[0]) == "king") {
							
							if(!in_array("King", $kits)){
								
								if($coins >= 800){
									
									$newCoins = $coins - 800;
									
									$kits[] = "King";
									$PlayerFile->set("Kits", $kits);
									$PlayerFile->set("Coins", $newCoins);
									
									$PlayerFile->save();
									
									$sender->sendMessage($this->prefix."§aYou have sucessfully purchased the kit §6King §afor§6 800 coins, you can use it at any time with the Command §f/kit king §ause!");
									
								} else {
									$sender->sendMessage($this->prefix."§cYou do not have enough coins to purchase the kit §6King");
									
									$missingcoins = 800 - $coins;
									
									$sender->sendMessage($this->prefix."Available Coins§7: §6".$coins);
									$sender->sendMessage($this->prefix."Missing Coins§7: §6".$missingcoins);
									$sender->sendMessage($this->prefix."Required Coins§7:§6 800");
								}
								
							} else { //gekauft
								if($sender instanceof Player){
									$sender->removeAllEffects();
									$sender->getInventory()->clearAll();
									$sender->sendMessage($this->prefix . "§fKit §o§l§6King §r§frecieved");
									$sender->getInventory()->setHelmet(Item::get(314, 0, 1));
									$sender->getInventory()->setChestplate(Item::get(315, 0, 1));
									$sender->getInventory()->setLeggings(Item::get(316, 0, 1));
									$sender->getInventory()->setBoots(Item::get(317, 0, 1));
									$sender->getInventory()->addItem(Item::get(267, 0, 1));
									$sender->getInventory()->addItem(Item::get(322, 0, 64));
									$sender->addEffect(Effect::getEffect(11)->setAmplifier(0)->setDuration(199980)->setVisible(false));
								} else {
									$sender->sendMessage($this->prefix . "§fKit only available ingame :D");
								}
							}
						}
						###RITTER###
						elseif (strtolower($args[0]) == "knight") {
							
							if(!in_array("Knight", $kits)){
								
								if($coins >= 1000){
									
									$newCoins = $coins - 1000;
									
									$kits[] = "Knight";
									$PlayerFile->set("Kits", $kits);
									$PlayerFile->set("Coins", $newCoins);
									
									$PlayerFile->save();
									
									$sender->sendMessage($this->prefix."§aYou have sucessfully purchased the kit §7night §afor§6 1000 coins, you can use it at any time with the Command §f/kit knight §ause");
									
								} else {
									$sender->sendMessage($this->prefix."§cYou do not have enough coins to purchase the kit §7Knight");
									
									$missingcoins = 1000 - $coins;
									
									$sender->sendMessage($this->prefix."Available Coins§7: §6".$coins);
									$sender->sendMessage($this->prefix."Missing Coins§7: §6".$missingcoins);
									$sender->sendMessage($this->prefix."Required Coins§7:§6 1000");
								}
								
							} else { //gekauft
								if($sender instanceof Player){
									$sender->removeAllEffects();
									$sender->getInventory()->clearAll();
									$sender->sendMessage($this->prefix . "§fKit §o§l§7Knight §r§frecieved");
									$sender->getInventory()->setHelmet(Item::get(306, 0, 1));
									$sender->getInventory()->setChestplate(Item::get(303, 0, 1));
									$sender->getInventory()->setLeggings(Item::get(308, 0, 1));
									$sender->getInventory()->setBoots(Item::get(309, 0, 1));
									$sender->getInventory()->addItem(Item::get(267, 0, 1));
									$sender->getInventory()->addItem(Item::get(322, 0, 5));
									$sender->getInventory()->addItem(Item::get(297, 0, 20));
									$sender->addEffect(Effect::getEffect(2)->setAmplifier(1)->setDuration(199980)->setVisible(false));
									$sender->addEffect(Effect::getEffect(11)->setAmplifier(1)->setDuration(199980)->setVisible(false));
								} else {
									$sender->sendMessage($this->prefix . "§fKit only available ingame :D");
								}
							}
						}
					}
                } else {
					$sender->sendMessage("§6-> §f/kit <kitname>");
					$sender->sendMessage("§6-> §aList all of the available kits with §f/kits");
				}
                break;
            case "spawn":
                $sender->getInventory()->clearAll();
                $sender->removeAllEffects();
                $sender->sendMessage($this->prefix . "§aYou are now on the spawn.");
                $sender->setHealth(0);
                break;
            case "mode":
                if (!$sender instanceof Player) {
                if (strtolower($args[0]) == "c" && $sender->isOP()) {
                    $sender->sendMessage($this->prefix . "§aYour gamemode has been changed to §cCREATIVE!");
                    $sender->setGamemode(1);
                }
                if (strtolower($args[0]) == "s" && $sender->isOP()) {
                    $sender->sendMessage($this->prefix . "§aYour gamemode has been changed to §cSURVIVAL!");
                    $sender->setGamemode(0);
                }
                if (strtolower($args[0]) == "a" && $sender->isOP()) {
                    $sender->sendMessage($this->prefix . "§aYour gamemode has been changed to §cADVENTURE!");
                    $sender->setGamemode(2);
                }
                if (strtolower($args[0]) == "spc" && $sender->isOP()) {
                    $sender->sendMessage($this->prefix . "§aYour gamemode has been changed to §cSPECTATOR!");
                    $sender->setGamemode(3);
                }
				}$sender->sendMessage("Ingame only!")
                break;
            case "feed":
                if ($sender->isOP() && $sender instanceof Player) {
                    $sender->setFood(20);
                    $sender->sendMessage($this->prefix . "§aYour appetite has been quenched!");
                } else {
                    $sender->sendMessage($this->prefix . "§4You do not have permission to use this command!");
                }
                break;
            case "heal":
                if ($sender->isOP() && $sender instanceof Player) {
                    $sender->setHealth(20);
                    $sender->sendMessage($this->prefix . "§aYou now have full health!");
                } else {
                    $sender->sendMessage($this->prefix . "§4You do not have permission to use this command!");
                }
                break;
            case "spc":
                if (!isset($args[0]) && $sender->hasPermission("vanish.use")) {
                    $sender->sendMessage($this->prefix . "§6-> §f/spc §7<§a+§7 | §c-§7>");
                }
                if ($args[0] != "+" &&
                        $args[0] != "-" && $sender->hasPermission("vanish.use")) {
                    $sender->sendMessage($this->prefix . "§6-> §f/spc §7<§a+§7 | §c-§7>");
                }
                if ($args[0] == "-" && $sender->hasPermission("vanish.use")) {
                    $sender->sendMessage($this->prefix . "§fYou have left §cVanishMode!");
                    $sender->removeAllEffects();
                    $sender->getInventory()->clearAll();
                } elseif ($args[0] == "+" && $sender->hasPermission("vanish.use")) {
                    $sender->removeAllEffects();
                    $sender->getInventory()->clearAll();
                    $sender->sendMessage($this->prefix . "§fYou have entered §aVanishMode!");
                    $sender->setGamemode(0);
                    $sender->addEffect(Effect::getEffect(14)->setAmplifier(1)->setDuration(199980)->setVisible(false));
                }
                break;
            case "cinv":
                if (!isset($args[0]) && $sender->isOP()) {
                    $sender->removeAllEffects();
                    $sender->getInventory()->clearAll();
                    $sender->sendMessage($this->prefix . "§aInventory cleared!");
                }
                if (isset($args[0]) && $sender->isOP()) {
                    $p = $args[0]->getPlayer();
                    $name = $p->getName();
                    $p->removeAllEffects();
                    $p->getInventory()->clearAll();
                    $p->sendMessage($this->prefix . "§aInventory cleared!");
                    $sender->sendMessage($this->prefix . "§aThe inventory of §b$name §ahas been cleared.");
                }
             break;
              case "gethealth":
				if($sender instanceof Player){
					$h = $sender->getHealth() /2;
					$sender->sendMessage("-> $h");
					$this->getLogger()->info("$name -> $h");
				}
		}
    }
}
