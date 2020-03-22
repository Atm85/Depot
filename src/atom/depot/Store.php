<?php

namespace atom\depot;

use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class Store {

    public static function buy(Player $player, Item $item, float $price): void{
        if ($price <= self::getMoney($player)){
            $player->getInventory()->addItem($item);
            $player->sendMessage(TextFormat::GREEN."You bought " . $item->getName() . " for $" . $price);
            self::removeMoney($player, $price);
        } else {
            $player->sendMessage(TextFormat::AQUA."You do not have enough money to buy!");
        }
    }

    public static function sell(Player $player, Item $item, float $price): void{
        if ($player->getInventory()->contains($item)) {
            $player->getInventory()->contains($item);
            $player->getInventory()->removeItem($item);
            $player->sendMessage(TextFormat::RED."You sold " . $item->getName() . " for $" . $price);
            self::addMoney($player, $price);
        } else {
            $player->sendMessage(TextFormat::AQUA . "You do not have enough " . $item->getName() . "'s to sell!");
        }
    }

    public function __construct() {
    }

    public static function addMoney(Player $player, int $amount) {
        $api = Main::getInstance()->getServer()->getPluginManager()->getPlugin("EconomyAPI");
        $newAmount = self::getMoney($player) + $amount;
        $api->setMoney($player, $newAmount, true);
        $api->saveAll();
    }

    public static function removeMoney(Player $player, int $amount) {
        $api = Main::getInstance()->getServer()->getPluginManager()->getPlugin("EconomyAPI");
        if (self::getMoney($player) <= 0) {
            return;
        } else {
            $newAmount = self::getMoney($player) - $amount;
            $api->setMoney($player, $newAmount, true);
            $api->saveAll();
        }
    }

    public static function getMoney(Player $player){
        $api = Main::getInstance()->getServer()->getPluginManager()->getPlugin("EconomyAPI");
        return $api->myMoney($player);
    }
}
