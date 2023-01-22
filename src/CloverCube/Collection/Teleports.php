<?php

declare(strict_types=1);

namespace CloverCube\Collection;

use pocketmine\world\Position;

class Teleports {

	private Position $position;

	private Position $teleport;

	public function __construct(Position $position, Position $teleport)
	{
		$this->position = $position;

		$this->teleport = $teleport;
	}

	public function getPosition(): Position
	{
		return $this->position;
	}

	public function getTeleport(): Position
	{
		return $this->teleport;
	}
}