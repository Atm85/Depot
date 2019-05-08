<?php

namespace atom\depot\manager;


use atom\depot\Main;
use atom\depot\Store;
use atom\gui\type\CustomGui;
use atom\gui\type\SimpleGui;
use atom\gui\GUI as Form;
use pocketmine\item\ItemFactory;
use pocketmine\Player;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;

class GUI {

    /** @var Config */
    private static $store;

    /** array [] */
    private static $category = [];

    /** array [] */
    private static $product = [];

    /** array */
    private static $identifier = [];

    /**
     * @param string $name
     */
    public static function register(string $name): void {
        self::$store = new Config(Main::getInstance()->getDataFolder()."store.yml", Config::YAML);
        $gui = new SimpleGui();
        $gui->setTitle("Merchant!");
        foreach (self::$store->getAll() as $category => $item){
            $gui->addButton($category);
            array_push(self::$category, $category);
            array_push(self::$product, $item);
        }
        $gui->setAction(function (Player $player, $data) {
            self::open($player, $data);
        });
        Form::register($name, $gui);
    }

    /**
     * @param Player $player
     * @param string $name
     */
    public static function send(Player $player, string $name): void {
        Form::send($player, $name);
    }

    private static function open(Player $player, $data): void {
        self::$identifier = [];
        $shop = new SimpleGui();
        $shop->setTitle($data);
        for ($i = 0; $i < count(self::$category); $i++) {
            if (self::$category[$i] == $data) {
                foreach (self::$product[$i] as $item) {
                    $split = explode("-", $item);
                    array_push(self::$identifier, $split);
                }
                for ($x = 0; $x < count(self::$identifier); $x++) {
                    $shop->addButton(self::$identifier[$x][2]);
                }
                $shop->addButton(TextFormat::DARK_RED.TextFormat::BOLD."✕ 'RETURN'");
            }
        }
        $shop->setAction(function (Player $player, $data){
            if ($data == TextFormat::DARK_RED.TextFormat::BOLD."✕ 'RETURN'"){
                self::send($player, "shop-gui");
            } else {
                self::transaction($player, $data);
            }
        });
        Form::register("shop", $shop);
        self::send($player, "shop");
    }

    private static function transaction(Player $player, $data){
        for ($i = 0; $i < count(self::$identifier); $i++) {
            if (self::$identifier[$i][2] == $data) {
                $price = self::$identifier[$i][1];
                $id = explode(":", self::$identifier[$i][0]);
                $transaction = new CustomGui();
                $transaction->setTitle("Buy/Sell: ".self::$identifier[$i][2]);
                $transaction->addLabel(TextFormat::YELLOW."Current server coins: $".Store::getMoney($player));
                $transaction->addLabel(TextFormat::GREEN."Buy for: $".$price);
                $transaction->addLabel(TextFormat::RED."Sell for: $".(25/100)*$price);
                $transaction->addToggle("Buy/Sell");
                $transaction->addSlider("Amount", 1, 64);
                $transaction->setAction(function (Player $player, $data) use ($price, $id) {
                    if (!$data[3]){
                        if (isset($id[1])){
                            $item = ItemFactory::get($id[0], $id[1], $data[4]);
                        } else {
                            $item = ItemFactory::get($id[0], 0, $data[4]);
                        }
                        $bulk_price = $price * $data[4];
                        Store::buy($player, $item, $bulk_price);
                    } else {
                        if (isset($id[1])) {
                            $item = ItemFactory::get($id[0], $id[1], $data[4]);
                        } else {
                            $item = ItemFactory::get($id[0], 0, $data[4]);
                        }
                        $bulk_price = $price * $data[4];
                        Store::sell($player, $item, (25/100)*$bulk_price);
                    }
                });
                Form::register("transaction", $transaction);
                self::send($player, "transaction");
            }
        }
    }
}
