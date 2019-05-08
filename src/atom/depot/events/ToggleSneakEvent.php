<?php

namespace atom\depot\events;

use atom\depot\Main;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerToggleSneakEvent;

class ToggleSneakEvent implements Listener{

    private $plugin;

    public function __construct(Main $plugin) {
        $this->plugin = $plugin;
    }

    /**
     * @param PlayerToggleSneakEvent $event
     * @todo 'Double sneak to open shop'
     */
    public function onSneak(PlayerToggleSneakEvent $event): void{
    }
}
