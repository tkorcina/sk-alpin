<?php
declare(strict_types=1);


namespace App\Model\Entity\Enum;

class EventType
{

	const TYPE_HIKING = 'hiking';
	const TYPE_WATER = 'water';
	const TYPE_SKI = 'ski';
	const TYPE_AIR = 'air';
	const TYPE_MOTO = 'moto';
	const TYPE_ORIENTEERING = 'orienteering';
	const TYPE_OTHER = 'other';

	public static function getLabels(): array
	{
		return [
			self::TYPE_HIKING => 'Pěší turistika',
			self::TYPE_WATER => 'Vodní turistika',
			self::TYPE_SKI => 'Lyžařská turistika',
			self::TYPE_AIR => 'Letecká turistika',
			self::TYPE_MOTO => 'Motoristická turistika',
			self::TYPE_ORIENTEERING => 'Orientační běh',
			self::TYPE_OTHER => 'Ostatní',
		];
	}

	public static function getLabel(string $type): string
	{
		return self::getLabels()[$type] ?? $type;
	}

}
