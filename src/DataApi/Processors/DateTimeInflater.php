<?php
declare(strict_types=1);

namespace Tp\DataApi\Processors;

use DateTimeImmutable;
use DateTimeInterface;
use Tp\InvalidParameterException;

class DateTimeInflater extends ProcessorWithPaths
{
	protected function convertValue($value, array $itemPath)
	{
		$onPath = $this->onPath($itemPath);

		if (
			!is_null($value)
			&& $onPath
		) {
			// Pozor, neprojde, pokud časové razítko obsahuje desetinnou část
			// vteřin. Viz https://bugs.php.net/bug.php?id=51950.
			$processed = DateTimeImmutable::createFromFormat(DateTimeInterface::ISO8601, $value);
			if ($processed === FALSE) {
				$errorPathArray = $itemPath;
				array_unshift($errorPathArray, '');
				$errorPathString = implode('/', $errorPathArray);
				throw new InvalidParameterException($errorPathString);
			}

			return $processed;
		}

		return $value;
	}
}