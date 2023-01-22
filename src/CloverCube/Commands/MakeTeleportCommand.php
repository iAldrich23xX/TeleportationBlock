<?php

declare(strict_types=1);

namespace CloverCube\Commands;

use CloverCube\TapTeleport;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;

class MakeTeleportCommand extends \pocketmine\command\Command {

	private TapTeleport $plugin;

	public function __construct(TapTeleport $plugin)
	{
		$this->plugin = $plugin;

		parent::__construct("maketp", "MakeTeleportCommand", "/maketp", ["mtp"]);
	}

	/**
	 * @inheritDoc
	 */
	public function execute(CommandSender $sender, string $commandLabel, array $args)
	{
		if ($sender instanceof Player){
			if(!$sender->hasPermission("maketp.command")){
				$sender->sendMessage(TextFormat::RED . "No tienes permiso para ejecutar este comando.");
				return;
			}

			$name = $sender->getName();

			if($this->plugin->existManage($name)){
				$sender->sendMessage(TextFormat::RED . "Ya estas creando un tp.");
				return;
			}

			$sender->sendMessage(TextFormat::GREEN . "Iniciando configuracion de un tp...");

			$this->plugin->addManage($name);

			$sender->sendMessage(TextFormat::AQUA . "Toca el primer bloque.");
		} else $sender->sendMessage(TextFormat::RED . "Ejecuta este comando dentro del juego.");
	}
}