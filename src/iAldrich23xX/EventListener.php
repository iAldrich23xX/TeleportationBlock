<?php

declare(strict_types=1);

namespace iAldrich23xX;

use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\utils\TextFormat;
use pocketmine\world\sound\EndermanTeleportSound;

class EventListener implements \pocketmine\event\Listener {

    public function __construct(
        private Loader $plugin
    ) {}

    public function onQuit(PlayerQuitEvent $event): void
    {
        if ($this->plugin->existManage($event->getPlayer()->getName())) $this->plugin->removeManage($event->getPlayer()->getName());
    }

    /**
     * @throws \JsonException
     */
    public function onInteract(PlayerInteractEvent $event): void
    {
        $player = $event->getPlayer();

        if ($player->hasPermission("maketp.command")) {
            $name = $player->getName();

            if ($this->plugin->existManage($name)) {
                $manage = $this->plugin->getManage($name);

                $block = $event->getBlock();

                $manage->saveCoords($manage->getSlot(), $block->getPosition());

                $player->sendMessage(TextFormat::GREEN . "Block {$manage->getSlot()} saved");
                $player->sendMessage(TextFormat::AQUA . "Name: {$block->getName()} X: {$block->getPosition()->getX()} Y: {$block->getPosition()->getY()} Z: {$block->getPosition()->getZ()}");

                if ($manage->getSlot() == 1) {
                    $player->sendMessage(TextFormat::YELLOW . "Touch the second block");
                } else if ($manage->getSlot() == 2) {
                    $player->sendMessage(TextFormat::LIGHT_PURPLE . "Teleport created successfully");

                    $manage->saveConfig();

                    $this->plugin->removeManage($name);
                }

                $manage->increase();
            }
        }
    }

    public function onMove(PlayerMoveEvent $event): void
    {
        if (empty($this->plugin->getTeleports())) return;

        $player = $event->getPlayer();

        foreach ($this->plugin->getTeleports() as $teleports) {
            $tPosition = $teleports->getPosition();
            $pPosition = $player->getPosition();

            if (
                (int) $pPosition->getX() == (int) $tPosition->getX() &&
                (int) $pPosition->getY() == (int) $tPosition->getY() &&
                (int) $pPosition->getZ() == (int) $tPosition->getZ() &&
                $pPosition->getWorld()->getDisplayName() == $tPosition->getWorld()->getDisplayName()
            ) {
                $player->getWorld()->addSound($teleports->getTeleport()->asVector3(), new EndermanTeleportSound($player), [$player]);

                $player->teleport($teleports->getTeleport());

                $player->getWorld()->addSound($teleports->getTeleport()->asVector3(), new EndermanTeleportSound($player), [$player]);
            }
        }
    }

}