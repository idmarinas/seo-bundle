<?php
/**
 * Copyright 2025 (C) IDMarinas - All Rights Reserved
 *
 * Last modified by "IDMarinas" on 25/11/2025, 18:10
 *
 * @project IDMarinas Seo Bundle
 * @see     https://github.com/idmarinas/seo-bundle
 *
 * @file    BookType.php
 * @date    19/11/2025
 * @time    16:27
 *
 * @author  Iván Diaz Marinas (IDMarinas)
 * @license BSD 3-Clause License
 *
 * @since   1.0.0
 */

namespace Idm\Bundle\Seo\Form\OpenGraph;

use Idm\Bundle\Seo\Form\DataMapper\OpenGraphTypeMapper;
use Idm\Bundle\Seo\Form\Type\ArrayFieldType;
use Idm\Bundle\Seo\Form\Type\DateTimeFieldType;
use Idm\Bundle\Seo\Form\Type\TextFieldType;
use Override;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Isbn;

/**
 * book:author - profile array - Who wrote this book.
 * book:isbn - string - The ISBN
 * book:release_date - datetime - The date the book was released.
 * book:tag - string array - Tag words associated with this book.
 */
final class BookType extends AbstractType
{
	/**
	 * @inheritDoc
	 */
	#[Override]
	public function configureOptions (OptionsResolver $resolver): void
	{
		$resolver->setDefaults([
			'translation_domain' => 'IdmSeoBundle',
			'required'           => true,
			'attr'               => ['class' => 'row'],
		]);
	}

	/**
	 * @inheritDoc
	 */
	#[Override]
	public function buildForm (FormBuilderInterface $builder, array $options): void
	{
		$builder
			->add('releaseDate', DateTimeFieldType::class, [
				'label'              => 'entity.og.book.release_date.label',
				'help'               => 'entity.og.book.release_date.help',
				'column_css_classes' => 'col-sm-12 col-md-6',
			])
			->add('isbn', TextFieldType::class, [
				'label'              => 'entity.og.book.isbn.label',
				'help'               => 'entity.og.book.isbn.help',
				'column_css_classes' => 'col-sm-12 col-md-6',
				'constraints'        => [new Isbn(),],
			])
			->add('author', ArrayFieldType::class, [
				'label'              => 'entity.og.book.author.label',
				'help'               => 'entity.og.book.author.help',
				'column_css_classes' => 'col-sm-12 col-md-6',
			])
			->add('tag', ArrayFieldType::class, [
				'label'              => 'entity.og.book.tag.label',
				'help'               => 'entity.og.book.tag.help',
				'column_css_classes' => 'col-sm-12 col-md-6',
			])
			->add('type', HiddenType::class, ['data' => 'book',])
		;

		$builder->setDataMapper(new OpenGraphTypeMapper('book'));
	}
}
