<?php

namespace Itzdvbravo\Tags;

use pocketmine\event\Listener;
use Itzdvbravo\Tags\TagManager;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\item\Item;

class EventListener implements Listener{
    private $plugin;
    private $delay;

    public function __construct(TagManager $plugin){
        $this->plugin = $plugin;
    }

    public function onInteract(PlayerInteractEvent $event){
        $player = $event->getPlayer();
        $item = $event->getItem();
        if (!$item->getId() === Item::CLOCK) return;
        if ($item->getNamedTag()->getTag("tag")) {
            if (empty($this->delay[strtolower($player->getName())]) or $this->delay[strtolower($player->getName())] < time()) {
                $tag = $item->getNamedTag()->getString("tag");
                $this->plugin->giveTag($player, $tag, $item);
                $this->delay[strtolower($player->getName())] = time();
            }
        }
    }
}

