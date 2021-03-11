<?php

namespace derc\antialt;

use pocketmine\block\SeaLantern;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerLoginEvent;
use pocketmine\level\sound\PopSound;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class main extends PluginBase implements Listener{

    public static $ins;
    public static $acdata;
    public static $ipdata;
    public static $cfg;


    public function onLoad()
    {
        self::$ins = $this;
    }

    public function onEnable()
    {
        $this->saveDefaultConfig();
        $this->saveResource("ipdata.yml");
        $this->saveResource("accountdata.yml");
        self::$ipdata = new Config($this->getDataFolder()."ipdata.yml", 2);
        self::$acdata = new Config($this->getDataFolder()."accountdata.yml", 2);
        self::$cfg = $this->getConfig();
        $this->getServer()->getPluginManager()->registerEvents($this, $this);

        $this->getLogger()->info("Setup Complete :D");

    }



    public function onJoin(PlayerLoginEvent $event){
        $p = $event->getPlayer();
        $ip = $p->getAddress();
            if(provider::existsIp($ip)){
                if(!provider::existsAc($p->getName())){
                    if(provider::getIpLoginCount($ip) >= self::$cfg->get("max-accounts-per-player")){
                        if(self::$cfg->get("action") == "kick"){
                            $p->kick("Alt Accounts aren't allowed on this server! Please use your main Microsoft account!");
                            $this->infoAllMods("§c{$p->getName()} tryed to join with an alt Account!");
                        }elseif (self::$cfg->get("action") == "ban"){
                            $p->setBanned(true);
                            $p->kick("This account is now longer able to join this server! Reason: Alt Account Detection");
                            $this->infoAllMods("§c{$p->getName()} tryed to join with an alt Account!");
                        }elseif (self::$cfg->get("action") == "ignore"){
                            $this->infoAllMods("§c{$p->getName()} tryed to join with an alt Account!");
                            return;

                        }
                        $this->getLogger()->info("{$p->getName()}, is an alt account! Actions done!");
                    }else{
                        provider::addAc($p->getName());
                        $this->getLogger()->info("{$p->getName()}, is now an verified account! Welcome");
                        provider::saveALL();
                    }
                }else{
                    $this->getLogger()->info("{$p->getName()}, is an verified account!");
                }

            }else{
                provider::addIp($ip);
                provider::addAc($p->getName());
                $this->getLogger()->info("{$p->getName()}, joined with an new ip address!");
                provider::saveALL();
            }

    }


    //chatapi
    public function infoAllMods(string $info){
        foreach ($this->getServer()->getOnlinePlayers() as $player){
            if($player->hasPermission("antialt.mod") or $player->hasPermission("antialt.admin")){
                $player->getLevel()->addSound(new PopSound($player));
                $player->sendMessage($info);
            }
        }
    }
    public function infoAllAdmins(string $info){
        foreach ($this->getServer()->getOnlinePlayers() as $player){
            if($player->hasPermission("antialt.admin")){
                $player->getLevel()->addSound(new PopSound($player));
                $player->sendMessage($info);
            }
        }
    }
}