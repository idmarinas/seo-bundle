<?php

/**
 * Copyright 2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 24/11/2025, 17:05
 *
 * @project IDMarinas Seo Bundle
 * @see     https://github.com/idmarinas/seo-bundle
 *
 * @file    functions.php
 * @date    24/11/2025
 * @time    16:16
 *
 * @author  Iván Diaz Marinas (IDMarinas)
 * @license BSD 3-Clause License
 *
 * @since   1.0.0
 */

namespace Idm\Bundle\Seo;

use Symfony\Component\Translation\TranslatableMessage;

if (!function_exists('Idm\Bundle\Seo\t')) {
	/**
	 * Create a Translatable Object using **IdmSeoBundle** as the default domain.
	 */
	function t (string $label, array $parameters = [], string $domain = 'IdmSeoBundle'): TranslatableMessage
	{
		return new TranslatableMessage($label, $parameters, $domain);
	}
}
