<?php
/**
 * Copyright 2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 18/11/2025, 15:55
 *
 * @project IDMarinas Seo Bundle
 * @see     https://github.com/idmarinas/seo-bundle
 *
 * @file    StringToDateTimeTransformer.php
 * @date    18/11/2025
 * @time    15:54
 *
 * @author  Iván Diaz Marinas (IDMarinas)
 * @license BSD 3-Clause License
 *
 * @since   1.0.0
 */

namespace Idm\Bundle\Seo\Form\DataTransformer;

use DateMalformedStringException;
use DateTime;
use DateTimeInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\Extension\Core\DataTransformer\BaseDateTimeTransformer;
use function is_string;

final class StringToDateTimeTransformer extends BaseDateTimeTransformer
{
	/**
	 * Transforms a string to a DateTime object.
	 *
	 * @param string $value A string of date time
	 *
	 * @throws TransformationFailedException If the given value is not a string
	 * @throws DateMalformedStringException
	 */
	public function transform (mixed $value): ?DateTime
	{
		if (empty($value)) {
			return null;
		}

		if (!is_string($value)) {
			throw new TransformationFailedException('Expected a string.');
		}

		if (str_contains($value, "\0")) {
			throw new TransformationFailedException('Null bytes not allowed');
		}

		return new DateTime($value);
	}

	/**
	 * Transforms a DateTime object to a string.
	 *
	 * @param DateTimeInterface $value A value as produced by PHP's date() function
	 *
	 * @throws TransformationFailedException If the given value is not a string, or could not be transformed
	 */
	public function reverseTransform (mixed $value): string
	{
		if (empty($value)) {
			return '';
		}

		if (!$value instanceof DateTimeInterface) {
			throw new TransformationFailedException('Expected a \DateTimeInterface.');
		}

		return $value->format('c');
	}
}
