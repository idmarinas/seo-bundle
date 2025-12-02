<?php
/**
 * Copyright 2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 28/11/2025, 16:19
 *
 * @project IDMarinas Seo Bundle
 * @see     https://github.com/idmarinas/seo-bundle
 *
 * @file    TitleMeta.php
 * @date    25/11/2025
 * @time    21:07
 *
 * @author  Iván Diaz Marinas (IDMarinas)
 * @license BSD 3-Clause License
 *
 * @since   1.0.0
 */

namespace Idm\Bundle\Seo\Provider\Meta;

final class TitleMeta
{
	public string $default;
	public string $separator;
	public string $prefix;
	public string $suffix;
	public array  $templates = [];

	public function __construct (array $config)
	{
		$this->default = $config['default'];
		$this->separator = $config['separator'];
		$this->prefix = $config['prefix'];
		$this->suffix = $config['suffix'];
		$this->templates = $config['templates'];
	}

	public function getTemplate (string $type): string
	{
		return $this->templates[$type] ?? $this->templates['title'];
	}
}
