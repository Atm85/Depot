<?php

namespace atom\depot;

use atom\depot\commands\DepotCommand;
use atom\depot\events\JoinEvent;
use atom\depot\events\ToggleSneakEvent;
use atom\depot\manager\GUI;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class Main extends PluginBase {

    public $store;
    public static $instance;

    public function onLoad(): void{
        $map = $this->getServer()->getCommandMap();
        $map->register("depot", new DepotCommand($this));
    }

    public function onEnable(): void{
        self::$instance = $this;
        @mkdir($this->getDataFolder());
        @mkdir($this->getDataFolder()."data/");
        $this->store = new Config($this->getDataFolder()."store.yml");
        $this->store = new Config($this->getDataFolder()."store.yml", Config::YAML, [
            "Blocks" => [
                "2-250-grass",
                "1-50-stone",
                "17-50-oak wood",
                "12-250-sand",
                "49-5000-obsidian"
            ],
            "Tools" => [
                "268-250-Wooden Sword",
                "269-250-Wooden Shovel",
                "270-250-Wooden Pickaxe",
                "271-250-Wooden Axe",
                "290-250-Wooden Hoe",
                "272-500-Stone Sword",
                "273-500-Stone Shovel",
                "274-500-Stone Pickaxe",
                "275-500-Stone Axe",
                "291-500-stone Hoe",
                "267-1000-Iron Sword",
                "256-1000-Iron Shovel",
                "257-1000-Iron Pickaxe",
                "258-1000-Iron Axe",
                "292-1000-Iron Hoe",
                "276-2500-Diamond Sword",
                "277-2500-Diamond Shovel",
                "278-2500-Diamond Pickaxe",
                "279-2500-Diamond Axe",
                "293-2500-Diamond Hoe",
                "261-500-Bow",
                "262-125-Arrow",
                "259-720-Flint & Steel"
            ],
            "Protection" => [
                "298-350-Leather Helmet",
                "299-350-Leather Tunic",
                "300-350-Leather Leggings",
                "301-350-Leather Boots",
                "314-500-Gold Helmet",
                "315-500-Gold Chestplate",
                "316-500-Gold leggings",
                "317-500-Gold Boots",
                "302-1000-Chain Helmet",
                "303-1000-Chain Chestplate",
                "304-1000-Chain Leggings",
                "305-1000-Chain Boots",
                "306-1500-Iron Helmet",
                "307-1500-Iron Chestplate",
                "308-1500-Iron Leggings",
                "309-1500-Iron Boots",
                "310-10500-Diamond Helmet",
                "311-10500-Diamond Chestplate",
                "312-10500-Diamond Leggings",
                "113-10500-Diamond Boots"
            ],
            "Food" => [
                "297-250-bread",
                "368-250-steak",
                "366-250-chicken",
                "320-250-porkchop",
                "391-50-carrot",
                "260-50-apple",
                "393-50-potato"
            ],
            "Alchemy" => [
                "374-500-Glass Bottle",
                "377-250-Blaze Power",
                "372-250-Nether Wart",
                "348-250-Glowstone Dust",
                "331-250-Redstone Dust",
                "382-250-Glistering Mellon",
                "370-250-Ghast Tear",
                "378-250-Magma Cream",
                "353-250-Sugar",
                "349:3-250-Pufferfish",
                "396-250-Golden Carrot",
                "376-250-Fermented Spider Eye",
                "414-250-Rabbit's Foot"
            ]
        ]);
        $this->store->save();

        $this->getServer()->getPluginManager()->registerEvents(new JoinEvent($this), $this);
        $this->getServer()->getPluginManager()->registerEvents(new ToggleSneakEvent($this), $this);

        GUI::register("shop-gui");
    }

    public static function getInstance(): Main{
        return self::$instance;
    }



}
