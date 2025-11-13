<?php
/**
 * Copyright 2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 13/11/2025, 13:36
 *
 * @project IDMarinas Seo Bundle
 * @see     https://github.com/idmarinas/seo-bundle
 *
 * @file    NoConflictingRobots.php
 * @date    12/11/2025
 * @time    18:05
 *
 * @author  Iván Diaz Marinas (IDMarinas)
 * @license BSD 3-Clause License
 *
 * @since   1.0.0
 */

namespace Idm\Bundle\Seo\Validator;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
final class NoConflictingRobots extends Constraint
{
	public string $message = 'The "robots" meta tag cannot contain conflicting values: "{{ conflict }}"';

	// You can use #[HasNamedArguments] to make some constraint options required.
	// All configurable options must be passed to the constructor.
	public function __construct (?array $groups = null, mixed $payload = null)
	{
		parent::__construct([], $groups, $payload);
	}
}
