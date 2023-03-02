<?php

declare(strict_types=1);

namespace iAldrich23xX;

use iAldrich23xX\Collection\Manage;
use iAldrich23xX\Collection\Teleports;
use iAldrich23xX\Commands\MakeTeleportCommand;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;
use pocketmine\world\Position;
use function array_key_exists;

class Loader extends PluginBase {

    /** @var Teleports[]  */
    private array $teleports = [];

    /** @var Manage[] */
    private array $manage = [];

    public function onEnable(): void
    {
        $this->updateTeleports();

        $this->getServer()->getCommandMap()->register("teleportation-block", new MakeTeleportCommand($this));

        $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);

        $this->getServer()->getLogger()->info(TextFormat::GREEN . "Plugin Teleportation Block enable");
    }

    public function addManage(string $name): void
    {
        $this->manage[$name] = new Manage($this);
    }

    public function removeManage(string $name): void
    {
        unset($this->manage[$name]);
    }

    public function existManage(string $name): bool
    {
        return array_key_exists($name, $this->manage);
    }

    public function getManage(string $name): Manage
    {
        return $this->manage[$name];
    }

    public function getTeleports(): array
    {
        return $this->teleports;
    }

    public function updateTeleports(): void
    {
        foreach($this->teleports as $key => $value) {
            unset($this->teleports[$key]);
        }

        $config = new Config($this->getDataFolder() . "config.yml", Config::YAML);

        $array = $config->getAll();

        foreach($array as $key => $value) {
            $nameWorld1 = $value[1]['world'];
            $nameWorld2 = $value[2]['world'];

            if (!$this->getServer()->getWorldManager()->isWorldLoaded($nameWorld1)) $this->getServer()->getWorldManager()->loadWorld($nameWorld1);
            if (!$this->getServer()->getWorldManager()->isWorldLoaded($nameWorld2)) $this->getServer()->getWorldManager()->loadWorld($nameWorld2);

            $world1 = $this->getServer()->getWorldManager()->getWorldByName($nameWorld1);
            $world2 = $this->getServer()->getWorldManager()->getWorldByName($nameWorld2);

            $this->teleports[$key] = new Teleports(new Position($value[1]['X'], $value[1]['Y'], $value[1]['Z'], $world1), new Position($value[2]['X'], $value[2]['Y'], $value[2]['Z'], $world2));
        }
    }

    public function onDisable(): void
    {
        $this->getServer()->getLogger()->info(TextFormat::RED . "Plugin TapTeleport disable");
    }

}