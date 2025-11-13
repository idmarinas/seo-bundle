<?php
/**
 * Copyright 2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 13/11/2025, 13:38
 *
 * @project IDMarinas Seo Bundle
 * @see     https://github.com/idmarinas/seo-bundle
 *
 * @file    NoConflictingRobotsValidator.php
 * @date    12/11/2025
 * @time    18:40
 *
 * @author  Iván Diaz Marinas (IDMarinas)
 * @license BSD 3-Clause License
 *
 * @since   1.0.0
 */

namespace Idm\Bundle\Seo\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

final class NoConflictingRobotsValidator extends ConstraintValidator
{
	/**
	 * Define contradictory pairs of robot directives.
	 * Only 'index' ↔ 'noindex' and 'follow' ↔ 'nofollow' are mutually exclusive.
	 */
	private const array CONFLICTS = [
		'index'    => 'noindex',
		'noindex'  => 'index',
		'follow'   => 'nofollow',
		'nofollow' => 'follow',
	];

	public function validate (mixed $value, NoConflictingRobots|Constraint $constraint): void
	{
		if (!$constraint instanceof NoConflictingRobots) {
			throw new UnexpectedTypeException($constraint, NoConflictingRobots::class);
		}

		if (!is_array($value)) {
			return;
		}

		$value = array_filter($value, fn($v) => in_array($v, self::CONFLICTS));
		$previous = null;

		foreach ($value as $directive) {
			if ($previous != $directive && in_array(self::CONFLICTS[$directive], $value, true)) {
				$previous = self::CONFLICTS[$directive];
				$this->context
					->buildViolation($constraint->message)
					->setParameter('{{ conflict }}', sprintf('%s + %s', $directive, self::CONFLICTS[$directive]))
					->addViolation()
				;
			}
		}
	}
}
