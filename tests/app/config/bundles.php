<?php
/**
 * Copyright 2024-2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 10/11/2025, 16:58
 *
 * @project IDMarinas Seo Bundle
 * @see     https://github.com/idmarinas/seo-bundle
 *
 * @file    bundles.php
 * @date    19/03/2025
 * @time    17:06
 *
 * @author  Iván Diaz Marinas (IDMarinas)
 * @license BSD 3-Clause License
 *
 * @since   1.0.0
 */

use DAMA\DoctrineTestBundle\DAMADoctrineTestBundle;
use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle;
use EasyCorp\Bundle\EasyAdminBundle\EasyAdminBundle;
use Idm\Bundle\Seo\IdmSeoBundle;
use Symfony\Bundle\DebugBundle\DebugBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\MakerBundle\MakerBundle;
use Symfony\Bundle\SecurityBundle\SecurityBundle;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Bundle\WebProfilerBundle\WebProfilerBundle;
use Symfony\UX\TwigComponent\TwigComponentBundle;
use Twig\Extra\TwigExtraBundle\TwigExtraBundle;
use Zenstruck\Foundry\ZenstruckFoundryBundle;

return [
	FrameworkBundle::class        => ['all' => true],
	DoctrineBundle::class         => ['all' => true],

	// Dev-Test Bundles
	TwigBundle::class             => ['all' => true],
	TwigComponentBundle::class    => ['all' => true],
	TwigExtraBundle::class        => ['all' => true],
	SecurityBundle::class         => ['all' => true],
	MakerBundle::class            => ['all' => true],
	DoctrineFixturesBundle::class => ['all' => true],
	DAMADoctrineTestBundle::class => ['all' => true],
	DebugBundle::class            => ['all' => true],
	WebProfilerBundle::class      => ['all' => true],
	ZenstruckFoundryBundle::class => ['all' => true],
	EasyAdminBundle::class        => ['all' => true],

	// This Bundle
	IdmSeoBundle::class           => ['all' => true],
];
