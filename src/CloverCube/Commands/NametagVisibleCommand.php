<?php

namespace CloverCube\Commands;

use CloverCube\TapTeleport;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

class NametagVisibleCommand extends \pocketmine\command\Command {

	private TapTeleport $plugin;

	public function __construct(TapTeleport $plugin)
	{
		$this->plugin = $plugin;

		parent::__construct("nametagvisible", "Nametag Visible Command", "/namev (player)", ["nv"]);
	}

	/**
	 * @inheritDoc
	 */
	public function execute(CommandSender $sender, string $commandLabel, array $args)
	{
		if(!$sender->hasPermission("maketp.command")){
			$sender->sendMessage(TextFormat::RED . "No tienes permiso para ejecutar este comando.");
			return;
		}

		if (count($args) < 1) {
			$sender->sendMessage(TextFormat::RED . "Usa /nv (player)");
			return;
		}

		$player = $this->plugin->getServer()->getPlayerExact($args[0]);

		if ($player == null) {
			$sender->sendMessage(TextFormat::RED . "Jugador no encontrado");
			return;
		}

		if ($player->isAlive()) {
			$player->setNameTagAlwaysVisible(false);
			$player->setNameTagVisible(false);
		}
	}

}