<?php
/**
 * Copyright 2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 06/11/2025, 11:21
 *
 * @project IDMarinas Seo Bundle
 * @see     https://github.com/idmarinas/seo-bundle
 *
 * @file    Profile.php
 * @date    05/11/2025
 * @time    15:43
 *
 * @author  Iván Diaz Marinas (IDMarinas)
 * @license BSD 3-Clause License
 *
 * @since   1.0.0
 */

namespace Idm\Bundle\Seo\Entity\OpenGraph\Type;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Embeddable]
class Profile
{
	#[ORM\Column]
	#[Assert\NotNull]
	public string $firstName = '';

	#[ORM\Column]
	#[Assert\NotNull]
	public string $lastName = '';

	#[ORM\Column]
	#[Assert\NotNull]
	public string $username = '';

	#[ORM\Column]
	#[Assert\NotNull]
	public string $gender = '';

//profile:first_name - string - A name normally given to an individual by a parent or self-chosen.
//profile:last_name - string - A name inherited from a family or marriage and by which the individual is commonly known.
//profile:username - string - A short unique string to identify them.
//profile:gender - enum(male, female) - Their gender.
}
