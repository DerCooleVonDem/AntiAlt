<?php

namespace derc\antialt;

use derc\antialt\main;
use pocketmine\utils\Config;

class provider{

    //allrouder
    public static function saveALL(){
        main::$ipdata->save();
        main::$acdata->save();
    }

    //Ip Stuff
    public static function addIp($ip){
        if(!main::$ipdata->exists($ip)){
            main::$ipdata->set($ip, 1);
        }else{
            main::$ipdata->set($ip, main::$ipdata->get($ip)+1);
        }
    }
    public static function existsIp($ip)
    {
        if(main::$ipdata->exists($ip)){
            return true;
        }else{
            return false;
        }
    }
    public static function getIpLoginCount($ip)
    {
        if(main::$ipdata->exists($ip)){
            return main::$ipdata->get($ip);

        }else{
            return false;
        }

    }










    //Account Stuff
    public static function addAc(string $name){
        if(!main::$acdata->exists($name)){
            main::$acdata->set($name, true);
        }
    }
    public static function existsAc(string $name){
        if(main::$acdata->exists($name)){
            return true;
        }else{
            return false;
        }
    }
}