<?php

declare(strict_types=1);

namespace iAldrich23xX\Collection;

use iAldrich23xX\Loader;
use pocketmine\utils\Config;
use pocketmine\world\Position;

class Manage {

    private array $config = [];

    private int $slot = 1;

    private Loader $plugin;

    public function __construct(Loader $plugin)
    {
        $this->plugin = $plugin;
    }

    /**
     * @throws \JsonException
     */
    public function saveConfig() : void
    {
        $config = new Config($this->plugin->getDataFolder() . "config.yml", Config::YAML);

        $arrayOld = $config->getAll();

        $arrayOld[] = $this->config;

        $config->setAll($arrayOld);
        $config->save();

        $this->plugin->updateTeleports();
    }

    public function getSlot(): int
    {
        return $this->slot;
    }

    public function saveCoords(int $slot, Position $coords): void
    {
        $this->config[$slot] = ['X' => $coords->getX(), 'Y' => $coords->getY() + 1, 'Z' => $coords->getZ(), 'world' => $coords->getWorld()->getFolderName()];
    }

    public function increase(): void
    {
        $this->slot++;
    }

}