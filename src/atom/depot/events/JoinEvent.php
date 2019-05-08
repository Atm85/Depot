<?php

namespace atom\depot\events;

use atom\depot\Main;
use atom\depot\manager\GUI;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;

class JoinEvent implements Listener {

    private $plugin;
    private $player;

    public function __construct(Main $plugin) {
        $this->plugin = $plugin;
    }

    public function onJoin(PlayerJoinEvent $event): void{
        $player = $event->getPlayer();
        $this->player = strtolower($player->getName());
        $files = scandir($this->plugin->getDataFolder() . "data/");
        if (!in_array($this->player.".yml", $files)) {
            $this->save();
        }
    }

    private function getPath(): string{
        return $this->plugin->getDataFolder() . "data/" . $this->player . ".yml";
    }

    private function save(): void{
        yaml_emit_file($this->getPath(),
            [
                "name" => $this->player,
                "money" => 1000
            ]);
    }

}
