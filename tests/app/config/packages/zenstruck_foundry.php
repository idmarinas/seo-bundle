<?php
/**
 * Copyright 2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 13/11/2025, 14:15
 *
 * @project IDMarinas Seo Bundle
 * @see     https://github.com/idmarinas/seo-bundle
 *
 * @file    zenstruck_foundry.php
 * @date    13/11/2025
 * @time    14:13
 *
 * @author  Iván Diaz Marinas (IDMarinas)
 * @license BSD 3-Clause License
 *
 * @since   1.0.0
 */

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Symfony\Config\ZenstruckFoundryConfig;

return static function (ZenstruckFoundryConfig $config): void {
	$config->enableAutoRefreshWithLazyObjects(false)->persistence()->flushOnce(true);
};
