<?php
/**
 * Copyright 2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 10/06/2025, 16:52
 *
 * @project IDMarinas Seo Bundle
 * @see     https://github.com/idmarinas/seo-bundle
 *
 * @file    SeoSitemapGenerateCommand.php
 * @date    06/06/2025
 * @time    18:34
 *
 * @author  Iván Diaz Marinas (IDMarinas)
 * @license BSD 3-Clause License
 *
 * @since   1.0.0
 */

namespace Idm\Bundle\Seo\Command;

use Exception;
use Idm\Bundle\Seo\Service\Sitemap\SitemapGenerator;
use Psr\Cache\CacheException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
	name       : 'idm:seo:sitemap:generate',
	description: 'Generates a sitemap.xml file.',
)]
class SeoSitemapGenerateCommand extends Command
{
	public function __construct (private readonly SitemapGenerator $generator)
	{
		parent::__construct();
	}

	protected function configure (): void
	{
		$this->addOption('invalidate', 'i', InputOption::VALUE_NONE, 'Invalidate the sitemap cache before generating it.');
	}

	protected function execute (InputInterface $input, OutputInterface $output): int
	{
		$io = new SymfonyStyle($input, $output);

		try {
			$invalidate = $input->getOption('invalidate');
			$this->generator->generate($invalidate);

			$io->success(sprintf('Sitemap %s successfully!', $invalidate ? 'generated' : 'updated'));

			return Command::SUCCESS;
		} catch (Exception|CacheException $e) {
			$io->error($e->getMessage());

			return Command::FAILURE;
		}
	}
}
