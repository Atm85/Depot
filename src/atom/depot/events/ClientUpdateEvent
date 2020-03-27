<?php

namespace atom\depot\events;

use Closure;

use atom\depot\Main;
use pocketmine\entity\Attribute;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\event\server\DataPacketSendEvent;
use pocketmine\network\mcpe\protocol\ModalFormRequestPacket;
use pocketmine\network\mcpe\protocol\NetworkStackLatencyPacket;
use pocketmine\network\mcpe\protocol\UpdateAttributesPacket;
use pocketmine\Player;
use pocketmine\scheduler\ClosureTask;

class ClientUpdateEvent implements Listener {

    /** @var Main */
    private $plugin;

    /** @var Closure[][] */
    private $callbacks = [];

    public function __construct(Main $plugin) {
        $this->plugin = $plugin;
    }

    private function onPacketSend(Player $player, Closure $closure) : void {
        $ts = mt_rand() * 1000;
        $pk = new NetworkStackLatencyPacket();
        $pk->timestamp = $ts;
        $pk->needResponse = true;
        $player->sendDataPacket($pk);
        $this->callbacks[$player->getId()][$ts] = $closure;
    }

    public function onDataPacketReceive(DataPacketReceiveEvent $event) : void {
        $packet = $event->getPacket();
        if($packet instanceof NetworkStackLatencyPacket && isset($this->callbacks[$id = $event->getPlayer()->getId()][$ts = $packet->timestamp])) {
            $cb = $this->callbacks[$id][$ts];
            unset($this->callbacks[$id][$ts]);
            if(count($this->callbacks[$id]) === 0) {
                unset($this->callbacks[$id]);
            }
            $cb();
        }
    }

    public function onDataPacketSend(DataPacketSendEvent $event) : void {
        $packet = $event->getPacket();
        $player = $event->getPlayer();
        if($packet instanceof ModalFormRequestPacket) {
            $this->plugin->getScheduler()->scheduleDelayedTask(new ClosureTask(function (int $currentTick) use($player) : void {
                if($player->isOnline()) {
                    $this->onPacketSend($player, static function() use($player) : void {
                        if($player->isOnline()) {
                            $pk = new UpdateAttributesPacket();
                            $pk->entityRuntimeId = $player->getId();
                            $pk->entries[] = $player->getAttributeMap()->getAttribute(Attribute::EXPERIENCE_LEVEL);
                            $player->sendDataPacket($pk);
                        }
                    });
                }
            }), 10);
        }
    }

    public function onPlayerQuit(PlayerQuitEvent $event) : void {
        unset($this->callbacks[$event->getPlayer()->getId()]);
    }

}
