<?php

declare(strict_types=1);

namespace iAldrich23xX\Commands;

use iAldrich23xX\TapTeleport;
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
				$sender->sendMessage(TextFormat::RED . "You do not have permission to run this command");
				return;
			}

			$name = $sender->getName();

			if($this->plugin->existManage($name)){
				$sender->sendMessage(TextFormat::RED . "You are already configuring a teleport");
				return;
			}

			$sender->sendMessage(TextFormat::GREEN . "Starting configuration...");

			$this->plugin->addManage($name);

			$sender->sendMessage(TextFormat::AQUA . "Touch the first block");
		} else $sender->sendMessage(TextFormat::RED . "Run this command in game");
	}
}