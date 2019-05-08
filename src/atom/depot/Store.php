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
            $player->sendMessage(TextFormat::AQUA . "You do not have enough " . $item->getName() . " to sell!");
        }
    }

    public function __construct() {
    }

    public static function addMoney(Player $player, int $amount) {
        $data = yaml_parse_file(self::getDataFile($player));
        $data['money'] += $amount;
        yaml_emit_file(self::getDataFile($player), [
            "name" => strtolower($player->getName()),
            "money" => $data['money']
        ]);
    }

    public static function removeMoney(Player $player, int $amount) {
        if (self::getMoney($player) <= 0) {
            return;
        } else {
            $data = yaml_parse_file(self::getDataFile($player));
            $data['money'] -= $amount;
            yaml_emit_file(self::getDataFile($player), [
                "name" => strtolower($player->getName()),
                "money" => $data['money']
            ]);
        }
    }

    public static function getMoney(Player $player){
        $data = yaml_parse_file(self::getDataFile($player));
        return $data['money'];
    }

    private static function getDataFile(Player $player) {
        return Main::getInstance()->getDataFolder()."data/".strtolower($player->getName()).".yml";
    }
}