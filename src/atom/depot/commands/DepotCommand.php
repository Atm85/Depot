<?php

namespace atom\depot\commands;

use atom\depot\Main;
use atom\depot\manager\GUI;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Player;

class DepotCommand extends PluginCommand {

    private $plugin;

    public function __construct(Main $plugin) {
        parent::__construct("depot", $plugin);
        $this->setDescription("Opens shop gui");
        $this->setAliases(["shop"]);
    }

    public function execute(CommandSender $sender, string $cmd, array $args) {
        if ($sender instanceof Player) {
            GUI::send($sender, "shop-gui");
        } else {
            return;
        }
    }
}
