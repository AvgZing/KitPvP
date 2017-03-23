<?php
namespace TheRoyalBlock\KitPvP;
//Blocks
use pocketmine\block\Block;
//Command
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
//Entity
use pocketmine\entity\Entity;
use pocketmine\entity\Effect;
//Events
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerItemHeldEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\entity\EntityLevelChangeEvent; 
use pocketmine\event\player\PlayerDropItemEvent;
//Inventory
use pocketmine\inventory\ChestInventory;
use pocketmine\inventory\EnderChestInventory;
//Item
use pocketmine\item\Item;
//Lang
//Level
use pocketmine\level\Level;
use pocketmine\level\Position;
//Math
use pocketmine\math\Vector3;
//Metadata
//Nbt
use pocketmine\nbt\NBT;
//Network
use pocketmine\network\Network;
//Permission
use pocketmine\permission\Permission;
//Plugin
use pocketmine\plugin\PluginBase;
//Scheduler
use pocketmine\scheduler\PluginTask;
//Tile
use pocketmine\tile\Sign;
use pocketmine\tile\Chest;
//Utils
use pocketmine\utils\TextFormat as C;
use pocketmine\utils\Config;
//Other
use pocketmine\Player;
use pocketmine\Server;
class KitPvP extends PluginBase implements Listener {
    public $prefix = "§7[§cKitPvP§7] §f";
//=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=
    public function onEnable() {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->getServer()->getLogger()->info ($this->prefix."§aKitPvP enabled!");
		@mkdir($this->getDataFolder());
		@mkdir($this->getDataFolder()."Players");
		@mkdir($this->getDataFolder()."Players/".strtolower($name{0}));
		@mkdir($this->getDataFolder()."Players/c/console.yml");
    }
    public function onDisable() {
        $this->getServer()->getLogger()->info ($this->prefix."§cKitPvP disabled!");
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
		
		@mkdir($this->getDataFolder());
		@mkdir($this->getDataFolder()."Players");
		@mkdir($this->getDataFolder()."Players/".strtolower($name{0}));
		@mkdir($this->getDataFolder()."Players/c/console.yml");
		
		$PlayerFile = new Config($this->getDataFolder()."Players/".strtolower($name{0})."/".strtolower($name).".yml", Config::YAML);
		
		if(empty($PlayerFile->get("Coins"))){
			$PlayerFile->set("Coins", 0);
		}
		if(empty($PlayerFile->get("Kits"))){
			$PlayerFile->set("Kits", array("Survivor"));
		}
		
		$PlayerFile->save();
    }
	
//=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=_=
	
    public function onCommand(CommandSender $sender, Command $cmd, $lable, array $args) {
		
		$name = $sender->getName();
		$PlayerFile = new Config($this->getDataFolder()."Players/".strtolower($name{0})."/".strtolower($name).".yml", Config::YAML);
		
		$kits = $PlayerFile->get("Kits",[]);
		$coins = $PlayerFile->get("Coins",[]);
		
        switch ($cmd->getName()) {
            case "kits":
				
				$sender->sendMessage("§7=_=_=_=_=_=_=_=_=_");
				$sender->sendMessage(" §7- §8Survivor §7[§aPurchased§7]");
				
				if(in_array("Maniac", $kits)){
					$sender->sendMessage(" §7- §bManiac §7[§aPurchased§7]");
				} else {
					$sender->sendMessage(" §7- §bManiac §7[§c250 coins§7]");
				}
				if(in_array("Prisoner", $kits)){
					$sender->sendMessage(" §7- §cPrisoner §7[§aPurchased§7]");
				} else {
					$sender->sendMessage(" §7- §cPrisoner §7[§c500 coins§7]");
				}
				if(in_array("Solid", $kits)){
					$sender->sendMessage(" §7- §aSolid §7[§aPurchased§7]");
				} else {
					$sender->sendMessage(" §7- §aSolid §7[§c750 coins§7]");
				}
				if(in_array("Demolisher", $kits)){
					$sender->sendMessage(" §7- §4Demolisher §7[§aPurchased§7]");
				} else {
					$sender->sendMessage(" §7- §4Demolisher §7[§c7500 coins§7]");
				}
				if(in_array("Lucky", $kits)){
					$sender->sendMessage(" §7- §fLucky §7[§aPurchased§7]");
				} else {
					$sender->sendMessage(" §7- §fLucky §7[§c10000 coins§7]");
				}
				if(in_array("Mad", $kits)){
					$sender->sendMessage(" §7- §6Mad §7[§aPurchased§7]");
				} else {
					$sender->sendMessage(" §7- §6Mad §7[§c100,000 coins§7]");
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
					if (strtolower($args[0]) != "survivor" &&
							strtolower($args[0]) != "maniac" &&
							strtolower($args[0]) != "prisoner" &&
							strtolower($args[0]) != "solid" &&
							strtolower($args[0]) != "demolisher" &&
							strtolower($args[0]) != "lucky" &&
							strtolower($args[0]) != "mad") {
						$sender->sendMessage($this->prefix . "§cThe kit §e$args[0] §cdoes not exist or there is a spelling error.");
						$sender->sendMessage("§6-> §f/kits");
					} else {
						###Survivor###
						if (strtolower($args[0] == "Survivor")) {
							if($sender instanceof Player){
								$sender->removeAllEffects();
								$sender->getInventory()->clearAll();
								$sender->sendMessage($this->prefix . "§fKit §o§l§8Survivor §r§frecieved");
								$sender->getInventory()->setHelmet(Item::get(298, 0, 1));
								$sender->getInventory()->setChestplate(Item::get(299, 0, 1));
								$sender->getInventory()->setLeggings(Item::get(300, 0, 1));
								$sender->getInventory()->setBoots(Item::get(301, 0, 1));
								$sender->getInventory()->addItem(Item::get(276, 0, 1));
								$sender->getInventory()->addItem(Item::get(322, 0, 6));
							} else {
								$sender->sendMessage($this->prefix . "§fKit only available ingame:D");
							}
						}
						###Maniac###
						elseif (strtolower($args[0]) == "Maniac") {
							
							if(!in_array("Maniac", $kits)){
								
								if($coins >= 250){
									
									$newCoins = $coins - 250;
									
									$kits[] = "Maniac";
									$PlayerFile->set("Kits", $kits);
									$PlayerFile->set("Coins", $newCoins);
									
									$PlayerFile->save();
									
									$sender->sendMessage($this->prefix."§aYou have successfully purchased the kit §bManiac §afor§6 250 coins, you can now use it at any time with the command §f/kit Maniac §ause!");
									
								} else {
									$sender->sendMessage($this->prefix."§cYou do not have enough coins to buy the kit §bManiac");
									
									$missingcoins = 250 - $coins;
									
									$sender->sendMessage($this->prefix."Available Coins§7: §6".$coins);
									$sender->sendMessage($this->prefix."Missing Coins§7: §6".$missingcoins);
									$sender->sendMessage($this->prefix."Required Coins§7:§6 250");
								}
								
							} else { //If already bought:
  
								if($sender instanceof Player){
									$sender->removeAllEffects();
									$sender->getInventory()->clearAll();
									$sender->sendMessage($this->prefix . "§fKit §o§l§bManiac §r§frecieved");
									$sender->getInventory()->setHelmet(Item::get(302, 0, 1));
									$sender->getInventory()->setChestplate(Item::get(303, 0, 1));
									$sender->getInventory()->setLeggings(Item::get(304, 0, 1));
									$sender->getInventory()->setBoots(Item::get(305, 0, 1));
									$sender->getInventory()->addItem(Item::get(283, 0, 1));
									$sender->getInventory()->addItem(Item::get(322, 0, 3));
								} else {
									$sender->sendMessage($this->prefix . "§fKit is only available ingame :D");
								}
							}
						}
						###Prisoner###
						elseif (strtolower($args[0]) == "Prisoner") {
							
							if(!in_array("Prisoner", $kits)){
								
								if($coins >= 500){
									
									$newCoins = $coins - 500;
									
									$kits[] = "Prisoner";
									$PlayerFile->set("Kits", $kits);
									$PlayerFile->set("Coins", $newCoins);
									
									$PlayerFile->save();
									
									$sender->sendMessage($this->prefix."§aYou have successfully purchased the kit §cPrisoner §afor§6 500 coins, you can use it at any time with the command §f/kit Prisoner §ause!");
									
								} else {
									$sender->sendMessage($this->prefix."§cYou do not have enough coins to buy the Kit §cPrisoner");
									
									$missingcoins = 500 - $coins;
									
									$sender->sendMessage($this->prefix."Available Coins§7: §6".$coins);
									$sender->sendMessage($this->prefix."Missing Coins§7: §6".$missingcoins);
									$sender->sendMessage($this->prefix."Required Coins§7:§6 500");
								}
								
							} else { //If already bought:
  
								if($sender instanceof Player){
									$sender->removeAllEffects();
									$sender->getInventory()->clearAll();
									$sender->sendMessage($this->prefix . "§fKit §o§l§cPrisoner §r§frecieved");
									$sender->getInventory()->addItem(Item::get(272, 0, 1));
									$sender->getInventory()->addItem(Item::get(322, 0, 1));
									$sender->getInventory()->setHelmet(Item::get(306, 0, 1));
									$sender->getInventory()->setChestplate(Item::get(307, 0, 1));
									$sender->getInventory()->setLeggings(Item::get(308, 0, 1));
									$sender->getInventory()->setBoots(Item::get(309, 0, 1));
								} else {
									$sender->sendMessage($this->prefix . "§fKit only available ingame :D");
								}
							}
						}
						###Solid###
						elseif (strtolower($args[0]) == "Solid") {
							
							if(!in_array("Solid", $kits)){
								
								if($coins >= 750){
									
									$newCoins = $coins - 750;
									
									$kits[] = "Solid";
									$PlayerFile->set("Kits", $kits);
									$PlayerFile->set("Coins", $newCoins);
									
									$PlayerFile->save();
									
									$sender->sendMessage($this->prefix."§aYou have sucessfully purchased the Kit §aSolid §afor§6 750 coins, you can now use it at any time with the Command §f/kit Solid §ause!");
									
								} else {
									$sender->sendMessage($this->prefix."§cYou do not have enough coins to buy the kit §aSolid");
									
									$missingcoins = 750 - $coins;
									
									$sender->sendMessage($this->prefix."Available Coins§7: §6".$coins);
									$sender->sendMessage($this->prefix."Missing Coins§7: §6".$missingcoins);
									$sender->sendMessage($this->prefix."Required Coins§7:§6 750");
								}
								
							} else { //If already bought:
  
								if($sender instanceof Player){
									$sender->removeAllEffects();
									$sender->getInventory()->clearAll();
									$sender->sendMessage($this->prefix . "§fKit §o§l§aSolid §r§fuse");
									$sender->getInventory()->addItem(Item::get(268, 0, 1));
									$sender->getInventory()->addItem(Item::get(322, 0, 2));
									$sender->getInventory()->setHelmet(Item::get(310, 0, 1));
									$sender->getInventory()->setChestplate(Item::get(311, 1));
									$sender->getInventory()->setLeggings(Item::get(312, 0, 1));
									$sender->getInventory()->setBoots(Item::get(313, 0, 1));
								} else {
									$sender->sendMessage($this->prefix . "§fKit only available ingame :D");
								}
							}
						}
						###Demolisher###
						elseif (strtolower($args[0]) == "Demolisher") {
							
							if(!in_array("Demolisher", $kits)){
								
								if($coins >= 7500){
									
									$newCoins = $coins - 7500;
									
									$kits[] = "Demolisher";
									$PlayerFile->set("Kits", $kits);
									$PlayerFile->set("Coins", $newCoins);
									
									$PlayerFile->save();
									
									$sender->sendMessage($this->prefix."§aYou have sucessfully purchased the kit §4Demolisher §afor§6 7500 coins, you can use it any time with the Command §f/kit Demolisher §ause!");
									
								} else {
									$sender->sendMessage($this->prefix."§cYou do not have enough coins to buy the kit §4Krieger");
									
									$missingcoins = 7500 - $coins;
									
									$sender->sendMessage($this->prefix."Available Coins§7: §6".$coins);
									$sender->sendMessage($this->prefix."Missing Coins§7: §6".$missingcoins);
									$sender->sendMessage($this->prefix."Required Coins§7:§6 7500");
								}
								
							} else { //If already bought:
  
								if($sender instanceof Player){
									$sender->removeAllEffects();
									$sender->getInventory()->clearAll();
									$sender->sendMessage($this->prefix . "§fKit §o§l§4Demolisher §r§frecieved");
									$enchantmentdem1 = Enchantment::getEnchantment(0);
									$enchantmentdem1->setLevel(1);
									$enchantmentdem2 = Enchantment::getEnchantment(9);
									$enchantmentdem2->setLevel(4);
									$helmet = Item::get(306, 0, 1);
									$chestplate = Item::get(307, 0, 1);
									$leggings = Item::get(308, 0, 1);
									$boots = Item::get(309, 0, 1);
									$sword = Item::get(283, 0, 1);
									$inv = $sender->getInventory();
									$helmet->addEnchantment($enchantmentdem1);
									$chestplate->addEnchantment($enchantmentdem1);
									$leggings->addEnchantment($enchantmentdem1);
									$boots->addEnchantment($enchantmentdem1);
									$sword->addEnchantment($enchantmentdem2);
									$inv->addItem($sword);
									$sender->getInventory()->addItem(Item::get(322, 0, 5));
									$inv->setHelmet($helmet);
									$inv->setChestplate($chestplate);
									$inv->setLeggings($leggings);
									$inv->setBoots($boots);
								} else {
									$sender->sendMessage($this->prefix . "§fKit only available in game:D");
								}
							}
						}
						###Lucky###
						elseif (strtolower($args[0]) == "Lucky") {
							
							if(!in_array("Lucky", $kits)){
								
								if($coins >= 10000){
									
									$newCoins = $coins - 10000;
									
									$kits[] = "Lucky";
									$PlayerFile->set("Kits", $kits);
									$PlayerFile->set("Coins", $newCoins);
									
									$PlayerFile->save();
									
									$sender->sendMessage($this->prefix."§aYou have sucessfully purchased the kit §fLucky §afor§6 10000 coins, you can use it anytime with the Command §f/kit Lucky §ause!");
									
								} else {
									$sender->sendMessage($this->prefix."§cYou do not have enough coins to purchase the kit §fLucky");
									
									$missingcoins = 10000 - $coins;
									
									$sender->sendMessage($this->prefix."Available Coins§7: §6".$coins);
									$sender->sendMessage($this->prefix."Missing Coins§7: §6".$missingcoins);
									$sender->sendMessage($this->prefix."Required Coins§7:§6 10000");
								}
								
							} else { //If already bought:
  
								if($sender instanceof Player){
									$sender->removeAllEffects();
									$sender->getInventory()->clearAll();
									$enchantmentdem1 = Enchantment::getEnchantment(0);
									$enchantmentdem1->setLevel(1);
									$enchantmentdem2 = Enchantment::getEnchantment(21);
									$enchantmentdem2->setLevel(1);
									$helmet = Item::get(310, 0, 1);
									$chestplate = Item::get(311, 0, 1);
									$leggings = Item::get(312, 0, 1);
									$boots = Item::get(313, 0, 1);
									$sword = Item::get(272, 0, 1);
									$inv = $sender->getInventory();
									$helmet->addEnchantment($enchantmentdem1);
									$chestplate->addEnchantment($enchantmentdem1);
									$leggings->addEnchantment($enchantmentdem1);
									$boots->addEnchantment($enchantmentdem1);
									$sword->addEnchantment($enchantmentdem2);
									$inv->addItem($sword);
									$sender->getInventory()->addItem(Item::get(322, 0, 2));
									$inv->setHelmet($helmet);
									$inv->setChestplate($chestplate);
									$inv->setLeggings($leggings);
									$inv->setBoots($boots);
								} else {
									$sender->sendMessage($this->prefix . "§fKit only available ingame :D");
								}
							}
						}
						###Mad###
						elseif (strtolower($args[0]) == "Mad") {
							
							if(!in_array("Mad", $kits)){
								
								if($coins >= 100000){
									
									$newCoins = $coins - 100000;
									
									$kits[] = "Mad";
									$PlayerFile->set("Kits", $kits);
									$PlayerFile->set("Coins", $newCoins);
									
									$PlayerFile->save();
									
									$sender->sendMessage($this->prefix."§aYou have sucessfully purchased the kit §6Mad §afor§6 100000 coins, you can use it at any time with the Command §f/kit Mad §ause!");
									
								} else {
									$sender->sendMessage($this->prefix."§cYou do not have enough coins to purchase the kit §6Mad");
									
									$missingcoins = 100000 - $coins;
									
									$sender->sendMessage($this->prefix."Available Coins§7: §6".$coins);
									$sender->sendMessage($this->prefix."Missing Coins§7: §6".$missingcoins);
									$sender->sendMessage($this->prefix."Required Coins§7:§6 100000");
								}
								
							} else { //If already bought:
  
								if($sender instanceof Player){
									$sender->removeAllEffects();
									$sender->getInventory()->clearAll();
									$sender->sendMessage($this->prefix . "§fKit §o§l§6Mad §r§frecieved");
									$sender->getInventory()->setHelmet(Item::get(314, 0, 1));
									$sender->getInventory()->setChestplate(Item::get(315, 0, 1));
									$sender->getInventory()->setLeggings(Item::get(316, 0, 1));
									$sender->getInventory()->setBoots(Item::get(317, 0, 1));
									$sender->getInventory()->addItem(Item::get(283, 0, 2));
									$sender->getInventory()->addItem(Item::get(285, 0, 2));
									$sender->getInventory()->addItem(Item::get(322, 0, 12));
									$enchantment = Enchantment::getEnchantment(22);
									$enchantment->setLevel(1);
									$book = Item::get(403, 0, 1);
									$book->addEnchantment($enchantment);
									$sender->getInventory()->addItem(Item::get($book));
									$sender->addEffect(Effect::getEffect(11)->setAmplifier(0)->setDuration(199980)->setVisible(false));
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
                $sender->setHealth(0); //lol thats one way of doing it!
		$sender->getInventory()->addItem(Item::get(322, 0, 12));
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
                break;
		}
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
	public function onMove(PlayerMoveEvent $event){
		$player = $event->getPlayer();
		$x = $player->getX();
		$y = $player->getY();
		$z = $player->getZ();
		$level = $player->getLevel();
		$block = $level->getBlock(new Vector3($x, $y-1, $z));
		if($block->getID() == 41){
			$direction = $player->getDirectionVector();
			$dx = $direction->getX();
			$dz = $direction->getZ();
			$player->knockBack($player, 0, $dx, $dz, 0.8);
			$player->setHealth(20);
		}
	}
}
?>
