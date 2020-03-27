<?php

namespace atom\depot;

use atom\depot\commands\DepotCommand;
use atom\depot\events\ClientUpdateEvent;
use atom\depot\manager\GUI;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class Main extends PluginBase {

    public $store;
    public static $instance;

    public function onLoad() : void {
        $map = $this->getServer()->getCommandMap();
        $map->register("depot", new DepotCommand($this));
    }

    public function onEnable() : void {
        self::$instance = $this;
        $this->saveResource("store.yml");
        $this->store = new Config($this->getDataFolder()."store.yml", Config::YAML);
        $this->store->save();
        $this->getServer()->getPluginManager()->registerEvents(new ClientUpdateEvent($this), $this);
        GUI::register("shop-gui");
    }

    public static function getInstance(): Main{
        return self::$instance;
    }



}
