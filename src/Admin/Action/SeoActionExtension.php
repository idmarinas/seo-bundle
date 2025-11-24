<?php
/**
 * Copyright 2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 24/11/2025, 18:25
 *
 * @project IDMarinas Seo Bundle
 * @see     https://github.com/idmarinas/seo-bundle
 *
 * @file    SeoActionExtension.php
 * @date    24/11/2025
 * @time    17:20
 *
 * @author  Iván Diaz Marinas (IDMarinas)
 * @license BSD 3-Clause License
 *
 * @since   1.0.0
 */

namespace Idm\Bundle\Seo\Admin\Action;

use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Action\ActionsExtensionInterface;
use Idm\Bundle\Seo\Entity\SeoEntityInterface;
use Override;
use Symfony\Contracts\Translation\TranslatorInterface;
use function Idm\Bundle\Seo\t;

final readonly class SeoActionExtension implements ActionsExtensionInterface
{
	public const string REMOVE = 'removeSeoOfEntity';
	public const string ADD    = 'addSeoToEntity';

	public function __construct (private TranslatorInterface $translator) {}

	#[Override]
	public function supports (AdminContext $context): bool
	{
		$crud = $context->getCrud();

		return ($crud->getCurrentPage() === Crud::PAGE_EDIT || $crud->getCurrentPage() === Crud::PAGE_INDEX)
		       && is_subclass_of($crud->getEntityFqcn(), SeoEntityInterface::class)
		       && method_exists($crud->getControllerFqcn(), self::ADD)
		       && method_exists($crud->getControllerFqcn(), self::REMOVE);
	}

	#[Override]
	public function extend (Actions $actions, AdminContext $context): void
	{
		$add = Action::new('seoAdd', t('admin.action.seo.add'))
			->linkToCrudAction(self::ADD)
			->asSuccessAction()
			->setIcon('fa fa-plus')
			->displayIf(fn($entity) => !$entity->hasSeo())
		;

		$remove = Action::new('seoRemove', t('admin.action.seo.remove.label'))
			->linkToCrudAction(self::REMOVE)
			->asDangerAction()
			->setIcon('fa fa-trash')
			->displayIf(fn($entity) => $entity->hasSeo())
			->setHtmlAttributes([
				'onclick' => 'return confirm("' . t('admin.action.seo.remove.confirm')->trans($this->translator) . '")',
			])
		;
		$actions
			->add(Action::EDIT, $add)
			->add(Action::EDIT, $remove)
			->add(Action::INDEX, $add)
			->add(Action::INDEX, $remove)
		;
	}
}
