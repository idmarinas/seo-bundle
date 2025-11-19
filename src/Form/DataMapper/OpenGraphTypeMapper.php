<?php
/**
 * Copyright 2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 19/11/2025, 18:03
 *
 * @project IDMarinas Seo Bundle
 * @see     https://github.com/idmarinas/seo-bundle
 *
 * @file    OpenGraphTypeMapper.php
 * @date    19/11/2025
 * @time    17:58
 *
 * @author  Iván Diaz Marinas (IDMarinas)
 * @license BSD 3-Clause License
 *
 * @since   1.0.0
 */

namespace Idm\Bundle\Seo\Form\DataMapper;

use Override;
use Symfony\Component\Form\DataMapperInterface;
use Traversable;

final readonly class OpenGraphTypeMapper implements DataMapperInterface
{
	public function __construct (private string $type) {}

	/**
	 * @inheritDoc
	 */
	#[Override]
	public function mapDataToForms (mixed $viewData, Traversable $forms): void
	{
		$forms = iterator_to_array($forms);
		$type = $viewData['type'] ?? null;

		foreach ($forms as $form) {
			$form->setData($type !== $this->type ? null : $viewData[$form->getName()]);
		}

		$forms['type']->setData($this->type);
	}

	/**
	 * @inheritDoc
	 */
	#[Override]
	public function mapFormsToData (Traversable $forms, mixed &$viewData): void
	{
		$forms = iterator_to_array($forms);
		$viewData = array_map(fn($form) => $form->getData(), $forms);
	}
}
