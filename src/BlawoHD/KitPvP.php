<?Php

Namespace BlawoHD;

Use pocketmine\Player;
Use pocketmine\plugin\PluginBase;
Use pocketmine\event\Listener;
Use pocketmine\command\Command;
Use pocketmine\command\CommandSender;
Use pocketmine\utils\Config;
Use pocketmine\entity\Effect;
Use pocketmine\item\item;
//Events
Use pocketmine\event\player\PlayerDropItemEvent;
Use pocketmine\event\player\PlayerJoinEvent;
Use pocketmine\event\player\PlayerDeathEvent;
Use pocketmine\event\entity\EntityDamageEvent;
Use pocketmine\event\entity\EntityDamageByEntityEvent;

Class KitPvP extends PluginBase implements listener {
  Public $prefix ="§7 [§cKitPvP§7] §f";
  //= _ = _ = _ = _ = _ = _ = _ =_ = _ = _ = _ = _ = _ = _ = _ = _ = _ = _ = _ = _ = _ = _ = _ = _ = _ = _=
  Public function onEnable(){
    $This->getServer()->getPluginManager()->registerEvents ($this, $this);
    $This->getServer()->getLogger()->info ($this->prefix."§aKitPvP enabled!");

    @mkdir ($this->getDataFolder ());
    @mkdir ($this->getDataFolder ()."Players");
  }
  Public function onDisable(){)
    $This->getServer()->getLogger()->info ($this->prefix."§cKitPvP disabled!");
  }
  //= _ = _ = _ = _ = _ = _ = _ = _ = _ = _ = _ = _ = _ = _ = _ = _ = _ = _ = _ = _ = _ = _ = _ = _ = _ = _
  Public function onDrop (PlayerDropItemEvent $event) {
    $Event->setCancelled (true);
  }

  Public function onDeath (PlayerDeathEvent $event) {
    $Entity = $event->getEntity ();
    $Cause = $entity->getLastDamageCause ();

    $Event->setDeathMessage("");

    If ($cause instanceof EntityDamageByEntityEvent) {
      $Killer = $cause->getDamager ();
      If ($killer instanceof Player) {
        $Name = $killer->getName ();
        $TargetFile = new Config ($this->getDataFolder ()."Players /" strtolower ($name {0})."/" Strtolower ($name)."Yml", Config :: YAML);

        $Targetcoins = $TargetFile->get("Coins");
        $NewCoins = $targetcoins + 10;
        $Addedcoins = 10;
        If ($killer->hasPermission("kitpvp.doublecoins") || $killer->isOP ()) {
          $NewCoins = $newCoins + 5;
          $Addedcoins = 10;
        }

        $TargetFile->set("Coins", $newCoins);
        $TargetFile->save ();

        $Killer->sendMessage ($this->prefix."§aYou have the player §b". $Entity->getName ()."§ kills. §f->§6 +". $Addedcoins."Coins") ;
      }
    }
  }

  Public function onJoin (PlayerJoinEvent $event) {
    $Player = $event->getPlayer ();
    $Name = $player->getName ();

    @mkdir ($this->getDataFolder ()."Players /". Strtolower ($name {0}));

    $PlayerFile = new Config ($this->getDataFolder ()."Players /" strtolower ($name {0})"/" strtolower ($name)."Yml", Config :: YAML);

    If (empty ($PlayerFile->get("Coins"))) {
      $PlayerFile->set("Coins", 0);
    }
    If (empty ($PlayerFile->get("Kits"))) {
      $PlayerFile->set("Kits", array("Viking"));
    }

    $PlayerFile->save ();
  }

  //= _ = _ = _ = _ = _ = _ = _ = _ = _ = _ = _ = _ = _ = _ = _ = _ = _ = _ = _ = _ = _ = _ = _ = _ = _ = _

  Public function onCommand (CommandSender $sender, Command $cmd, $lable, array $args) {

    $Name = $sender->getName ();
    $PlayerFile = new Config ($this->getDataFolder ()."Players /" strtolower ($name {0})"/" strtolower ($name)."Yml", Config :: YAML);

    $Kits = $PlayerFile->get("Kits");
    $Coins = $PlayerFile->get("Coins");

    Switch ($cmd->getName ()) {
      Case"kits":

      $Sender->sendMessage("§7 = _ = _ = _ = _ = _ = _ = _ = _ = _");
      $Sender->sendMessage("§7- §8Wikinger §7 [§aPurchase§7]");

      If (in_array("Ninja", $kits)) {
        $Sender->sendMessage("§7- §bNinja §7 [§aPurchase§7]");
      } Else {
        $Sender->sendMessage("§7- §bNinja §7 [§c150 coins§7]");
      }
      If (in_array("Mario", $kits)) {
        $Sender->sendMessage("§7- §cMario §7 [§APurchase§7]");
      } Else {
        $Sender->sendMessage("§7- §cMario §7 [§c200 coins§7]");
      }
      If (in_array("Archer", $kits)) {
        $Sender->sendMessage("§7- §aArcher §7 [§aPurchase§7]");
      } Else {
        $Sender->sendMessage("§7- §aArcher §7 [§c360 coins§7]");
      }
      If (in_array("Warrior", $kits)) {
        $Sender->sendMessage("§7- §4Warrior §7 [§APurchase§7]");
      } Else {
        $Sender->sendMessage("§7- §4Warrior §7 [§c500 coins§7]");
      }
      If (in_array("turbo", $kits)) {
        $Sender->sendMessage("§7- §fTurbo §7 [§aPurchase§7]");
      } Else {
        $Sender->sendMessage("§7- §fTurbo §7 [§c750 coins§7]");
      }
      If (in_array("King", $kits)) {
        $Sender->sendMessage("§7- §6King §7 [§APurchase§7]");
      } Else {
        $Sender->sendMessage("§7- §6King §7 [§c800 coins§7]");
      }
      If (in_array("knight", $kits)) {
        $Sender->sendMessage("§7- §7Ritter §7 [§APurchase§7]");
      } Else {
        $Sender->sendMessage("§7- §7Ritter§7 [§c1000 coins§7]");
      }
      $Sender->sendMessage("");
      $Sender->sendMessage("§9Kit select§7:");
      $Sender->sendMessage("§c /kit <KitName>");
      $Sender->sendMessage("§7 = _ = _ = _ = _ = _ = _ = _ = _ = _");

      Break;
      Case"coins":
      $Sender->sendMessage ($this->prefix."You have §6". $Coins."§fCoins!");
      Break;
      Case"setcoins":
      If ($sender->isOP ()) {
        If (! Empty ($args [0]) &&! Empty ($args [1])) {

          $Targetname = $args [0];
          If (file_exists ($this->getDataFolder()"Players /" strtolower ($targetname {0})"/" strtolower ($targetname)
          $TargetFile = new Config ($this->getDataFolder ()."Players /" strtolower ($targetname {0})"/" strtolower ($targetname)."Yml", Config :: YAML);

          $TargetFile->set("Coins", (int) $args [1]);
          $TargetFile->save ();

          $Sender->sendMessage ($this->prefix."You have the coins of §6". $Targetname."§fon §6". $Args [1]."§fset!");
        } Else {
          $Sender->sendMessage("Player does not exist!");
        }

      } Else {
        $Sender->sendMessage("/setcoins <player> <amount>");
      }
    }
    Break;
    Case"addcoins":
    If ($sender->isOP ()) {
      If (! Empty ($args [0]) &&! Empty ($args [1])) {

        $Targetname = $args [0];
        If (file_exists ($this->getDataFolder()"Players /" strtolower ($targetname {0})"/" strtolower ($targetname)
        $TargetFile = new Config ($this->getDataFolder ()."Players /" strtolower ($targetname {0})"/" strtolower ($targetname)."Yml", Config :: YAML);

        $Targetcoins = $TargetFile->get("Coins");
        $NewCoins = $targetcoins + (int) $args [1];

        $TargetFile->set("Coins", (int) $newCoins);
        $TargetFile->save ();

        $Sender->sendMessage ($this->prefix."You have the coins of §6". $Targetname."§fabout §6". $Args [1]."§felevated!");
      } Else {
        $Sender->sendMessage("Player does not exist!");
      }

    } Else {
      $Sender->sendMessage("/addcoins <player> <amount>");
    }
  }
  Break;
  Case"kit":
  If (! Empty ($args [0])) {
    If (strtolower ($args [0])! ="Viking" &&
    Strtolower ($args [0])! ="Ninja" &&
    Strtolower ($args [0])! ="Mario" &&
    Strtolower ($args [0])! ="Archer" &&
    Strtolower ($args [0])! ="Warrior" &&
    Strtolower ($args [0])! ="Turbo" &&
    Strtolower ($args [0])! ="King" &&
    Strtolower ($args [0])! ="Ritter") {
      $Sender->sendMessage ($this->prefix."§cThe kit $args [0] does not exist or there is a write error.");
      $Sender->sendMessage("§6->§f /kits");
    } Else {
      ### WIKINGER ###
      If (strtolower ($args [0] =="viking")) {
        If ($sender instanceof Player) {
          $Sender->removeAllEffects ();
          $Sender->getInventory()->clearAll ();
          $Sender->sendMessage ($this->prefix."§fKit §o§l§8Winger's §referenced");
          $Sender->getInventory()->setHelmet (Item :: get (306, 0, 1));
          $Sender->getInventory()->setChestplate (Item :: get (299, 0, 1));
          $Sender->getInventory()->setLeggings (Item :: get (300, 0, 1));
          $Sender->getInventory()->setBoots (Item :: get (301, 0, 1));
          $Sender->getInventory()->addItem (Item :: get (322, 0, 2));
          $Sender->getInventory()->addItem (Item :: get (350, 0, 20));
          $Sender->getInventory()->addItem (Item :: get (258, 0, 1));
          $Sender->addEffect (Effect :: getEffect (5) ->setAmplifier (0) ->setDuration (199980) ->setVisible (false));
        } Else {
          $Sender->sendMessage ($this->prefix."§fKit only ingame available: D");
        }
      }
      ### NINJA ###
      Elseif (strtolower ($args [0]) =="ninja") {

        If (! In_array("Ninja", $kits)) {

          If ($coins> = 150) {

            $NewCoins = $coins - 150;

            $Kits [] ="ninja";
            $PlayerFile->set("kits", $kits);
            $PlayerFile->set("Coins", $newCoins);

            $PlayerFile->save ();

            $Sender->sendMessage ($this->prefix."§aYou have successfully purchased the kit §bNinja §afor§6 150 coins, you can now at any time with the command §f /kit Ninja §ause!");

          } Else {
            $Sender->sendMessage ($this->prefix."" You do not have enough coins to buy the kit §bNinja");

            $Missingcoins = 150 - $coins;

            $Sender->sendMessage ($this->prefix."Current Coins§7: §6". $Coins);
            $Sender->sendMessage ($this->prefix."Missing Coins§7: §6". $Missingcoins);
            $Sender->sendMessage ($this->prefix."Required Coins§7: §6 150");
          }

        } Else {//)
          If ($sender instanceof Player) {
            $Sender->removeAllEffects ();
            $Sender->getInventory()->clearAll ();
            $Sender->sendMessage ($this->prefix."§fKit §o§l§bNinja §r§frecieve");
            $Sender->getInventory()->addItem (Item :: get (276, 1010, 1));
            $Sender->getInventory()->addItem (Item :: get (322, 0, 2));
            $Sender->getInventory()->addItem (Item :: get (260, 0, 20));
            $Sender->getInventory()->setHelmet (Item :: get (298, 0, 1));
            $Sender->getInventory()->setChestplate (Item :: get (299, 0, 1));
            $Sender->getInventory()->setLeggings (Item :: get (300, 0, 1));
            $Sender->getInventory()->setBoots (Item :: get (301, 0, 1));
            $Sender->addEffect (Effect :: getEffect (1) ->setAmplifier (1) ->setDuration (199980) ->setVisible (false));
          } Else {
            $Sender> SendMessage ($this->prefix."§fKit only in-game available: D");
          }
        }
      }
      ### KING ###
      Elseif (strtolower ($args [0]) =="king") {

        If (! In_array("King", $kits)) {

          If ($coins> = 800) {

            $NewCoins = $coins - 800;

            $Kits [] ="King";
            $PlayerFile->set("kits", $kits);
            $PlayerFile->set("Coins", $newCoins);

            $PlayerFile->save ();

            $Sender->sendMessage ($this->prefix."§aYou have successfully purchased the kit §6King §afor§6 800 coins, you can use it at any time with the command §f /kit King §ause!));

          } Else {
            $Sender->sendMessage ($this->prefix."" You do not have enough coins to buy the kit §6King");

            $Missingcoins = 800 - $coins;

            $Sender->sendMessage ($this->prefix."Current Coins§7: §6". $Coins);
            $Sender->sendMessage ($this->prefix."Missing Coins§7: §6". $Missingcoins);
            $Sender->sendMessage ($this->prefix."Required Coins§7: §6 800");
          }

        } Else {//)
          If ($sender instanceof Player) {
            $Sender->removeAllEffects ();
            $Sender->getInventory()->clearAll ();
            $Sender->sendMessage ($this->prefix."§fKit §o§l§6König §r§ferhalten");
            $Sender->getInventory()->setHelmet (Item :: get (314, 0, 1));
            $Sender->getInventory()->setChestplate (Item :: get (315, 0, 1));
            $Sender->getInventory()->setLeggings (Item :: get (316, 0, 1));
            $Sender->getInventory()->setBoots (Item :: get (317, 0, 1));
            $Sender->getInventory()->addItem (Item :: get (267, 0, 1));
            $Sender->getInventory()->addItem (Item :: get (322, 0, 64));
            $Sender->addEffect (Effect :: getEffect (11) ->setAmplifier (0) ->setDuration (199980) ->setVisible (false));
          } Else {
            $Sender->sendMessage ($this->prefix."§fKit only ingame available: D");
          }
        }
      }
      ###KNIGHT###
      Elseif (strtolower ($args [0]) =="ritter") {

        If (! In_array("knight", $kits)) {

          If ($coins> = 1000) {

            $NewCoins = $coins - 1000;

            $Kits [] ="Knight";
            $PlayerFile->set("kits", $kits);
            $PlayerFile->set("Coins", $newCoins);

            $PlayerFile->save ();

            $Sender->sendMessage ($this->prefix."§aYou have successfully purchased the Kit §7Ritter §afor§6 1000 coins, you can always use the command §f /kit Ritter §ause!));

          } Else {
            $Sender->sendMessage ($this->prefix."" You do not have enough coins to buy the kit §7Ritter");

            $Missingcoins = 1000 - $coins;

            $Sender->sendMessage ($this->prefix."Current Coins§7: §6". $Coins);
            $Sender->sendMessage ($this->prefix."Missing Coins§7: §6". $Missingcoins);
            $Sender->sendMessage ($this->prefix."Required Coins§7: §6 1000");
          }

        } Else {//)
          If ($sender instanceof Player) {
            $Sender->removeAllEffects ();
            $Sender->getInventory()->clearAll ();
            $Sender->sendMessage ($this->prefix."§fKit §o§l§7Ritter §rSferhalten");
            $Sender->getInventory()->setHelmet (Item :: get (306, 0, 1));
            $Sender->getInventory()->setChestplate (Item :: get (303, 0, 1));
            $Sender->getInventory()->setLeggings (Item :: get (308, 0, 1));
            $Sender->getInventory()->setBoots (Item :: get (309, 0, 1));
            $Sender->getInventory()->addItem (Item :: get (267, 0, 1));
            $Sender->getInventory()->addItem (Item :: get (322, 0, 5));
            $Sender->getInventory()->addItem (Item :: get (297, 0, 20));
            $Sender->addEffect (Effect :: getEffect (2) ->setAmplifier (1) ->setDuration (199980) ->setVisible (false));
            $Sender->addEffect (Effect :: getEffect (11) ->setAmplifier (1) ->setDuration (199980) ->setVisible (false));
          } Else {
            $Sender->sendMessage ($this->prefix."§fKit only ingame available: D");
          }
        }
      }
    }
  } Else {
    $Sender->sendMessage("§6->§f /kit <kitname>");
    $Sender->sendMessage("§6->§a list with all kits you see with §f /kits");
  }
  Break;
  Case"spawn":
  $Sender->getInventory()->clearAll ();
  $Sender->removeAllEffects ();
  $Sender->sendMessage ($this->prefix."§aYou are now on the spawn.");
  $Sender->setHealth (0);
  Break;
  Case"mode":
  If (! $Sender->isOP ()) {
    $Sender->sendMessage ($this->prefix."§4You do not have permission to use this command.!");
  }
  If (! $Sender instanceof Player) {
    $Sender->sendMessage ($this->prefix."§4Only ingame!");
  }
  If (strtolower ($args [0]) =="c" && $sender->isOP ()) {
    $Sender->sendMessage ($this->prefix."§AGamemode changed to §cCREATIVE!);
    $Sender->setGamemode (1);
  }
  If (strtolower ($args [0]) =="s" && $sender->isOP ()) {
    $Sender->sendMessage ($this->prefix."§aGamemode changed to §cSURVIVAL!);
    $Sender->setGamemode (0);
  }
  If (strtolower ($args [0]) =="a" && $sender->isOP ()) {
    $Sender->sendMessage ($this->prefix."§AGamemode changed to §cADVENTURE!!);
    $Sender->setGameMode (2);
  }
  If (strtolower ($args [0]) =="spc" && $sender->isOP ()) {
    $Sender->sendMessage ($this->prefix."§AGamemode changed to §cSPECTATOR!");
    $Sender->setGamemode (3);
  }
  Break;
  Case"feed":
  If ($sender->isOP()&& $sender instanceof Player) {
    $Sender->setFood (20);
    $Sender->sendMessage ($this->prefix.);
  } Else {
    $Sender->sendMessage ($this->prefix."§4You do not have permission to use this command.!");
  }
  Break;
  Case"heal":
  If ($sender->isOP()&& $sender instanceof Player) {
    $Sender->setHealth (20);
    $Sender->sendMessage ($this->prefix."§aYou are now full health!");
  } Else {
    $Sender->sendMessage ($this->prefix."§4You do not have permission to use this command.!");
  }
  Break;
  Case"spc":
  If (! Isset ($args [0]) && $sender->hasPermission("vanish.use")) {
    $Sender->sendMessage ($this->prefix."§6->§f /spc §7 <§a + §7 | §c-§7>");
  }
  If ($args [0]! ="+" &&
  $Args [0]! ="-" && $sender->hasPermission("vanish.use")) {
    $Sender->sendMessage ($this->prefix."§6->§f /spc §7 <§a + §7 | §c-§7>");
  }
  If ($args [0] =="-" && $sender->hasPermission("vanish.use")) {
    $Sender->sendMessage ($this->prefix."§fVanishMode §cLAVE!");
    $Sender->removeAllEffects ();
    $Sender->getInventory()->clearAll ();
  } Else {

  }
  If ($args [0] =="+" && $sender->hasPermission("vanish.use")) {
    $Sender->removeAllEffects ();
    $Sender->getInventory()->clearAll ();
    $Sender->sendMessage ($this->prefix."§fYou have entered §AVanishMode!");
    $Sender->setGamemode (0);
    $Sender->addEffect (Effect :: getEffect (14) ->setAmplifier (1) ->setDuration (199980) ->setVisible (false));
  }
  Break;
  Case"cinv":
  If (! Isset ($args [0]) && $sender->isOP ()) {
    $Sender->removeAllEffects ();
    $Sender->getInventory()->clearAll ();
    $Sender->sendMessage ($this->prefix."§AInventory cleared!");
  }
  If (isset ($args [0]) && $sender->isOP ()) {
    $P = $args [0] ->getPlayer ();
    $Name = $p->getName ();
    $P->removeAllEffects ();
    $P->getInventory()->clearAll ();
    $P->sendMessage ($this->prefix."§AInventory cleared!");
    $Sender->sendMessage ($this->prefix."§aThe inventory of §b $name §ahas been cleared.");
  }
  Break;
  Case"gethealth":
  If ($sender instanceof Player) {
    $H = $sender->getHealth()/2;
    $Sender->sendMessage("->$h");
    $This->getLogger()->info("$name ->$h");
  }
}
}
}
?>
