<?php

namespace App\Form;

use App\Entity\Author;
use App\Entity\Book;
use PharIo\Manifest\Author as ManifestAuthor;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('ref')
            ->add('title')
            ->add('category')
            ->add('publicationdate')
            ->add('published')
            ->add('author',EntityType::class,['class'=>Author::class, 'choice_label'=>'username','multiple'=>false,'expanded'=>false])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
        ]);
    }
}
