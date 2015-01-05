<?php

namespace Orkestro\Bundle\ProductBundle\Form;

use Orkestro\Bundle\CoreBundle\Form\AbstractTranslatableType;
use Orkestro\Bundle\LocaleBundle\Entity\LocaleRepository;
use Orkestro\Bundle\ProductBundle\Entity\Characteristic\CharacteristicRepository;
use Orkestro\Bundle\ProductBundle\Form\Characteristic\CharacteristicSelectorType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProductType extends AbstractTranslatableType
{
    protected $characteristicRepository;

    public function __construct(LocaleRepository $localeRepository, CharacteristicRepository $characteristicRepository)
    {
        parent::__construct($localeRepository);
        $this->characteristicRepository = $characteristicRepository;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('translations', 'a2lix_translations', array(
                    'locales' => $this->getLocales(),
                    'fields' => array(
                        'title' => array(
                            'field_type' => 'text',
                        ),
                        'url' => array(
                            'field_type' => 'text',
                        ),
                        'shortDescription' => array(
                            'field_type' => 'textarea',
                        ),
                        'fullDescription' => array(
                            'field_type' => 'textarea',
                        ),
                    ),
                ))
            ->add('sku')
            ->add('kind', 'entity', array(
                    'class' => 'OrkestroProductBundle:ProductKind',
                    'required' => true,
                ))
            ->add('characteristics', 'collection', array(
                    'type' => new CharacteristicSelectorType($this->localeRepository, $this->characteristicRepository),
                    'allow_add' => true,
                    'allow_delete' => true,
                    'by_reference' => false,
                ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Orkestro\Bundle\ProductBundle\Entity\Product'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'orkestro_bundle_productbundle_product';
    }
}
