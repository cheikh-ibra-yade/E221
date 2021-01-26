<?php

namespace App\Form;

use App\Entity\Bien;
use App\Repository\ZoneRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class BienType extends AbstractType
{
    private ZoneRepository $repoZon;   

    public function __construct(ZoneRepository $rep){
        $this->repoZon = $rep;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $zones = $this->repoZon->findAll();
        $zones = array_merge(['Ajouter une autre' => null], $zones);

        $builder
            ->add('titre')
            ->add('description',null,["attr"=>["placeholder"=>"Description","class"=>"form-control","rows"=>5]])
            ->add('type',ChoiceType::class, [
            "choices"=> Bien::TYPE,
            "placeholder"=>"Selectionner le type du bien"
            ])
            ->add('montant',null,["attr"=>["placeholder"=>"Montan de la location"]])
            ->add('periode',ChoiceType::class, [
            "choices"=> Bien::PERIODE,
            "placeholder"=>"Selectionner la période de location"])
            ->add('typeUsage',ChoiceType::class, [
            "choices"=> Bien::TYPE_USAGE,
            "placeholder"=>"Selectionner le type d'usage"])
            ->add('imageFile',VichImageType::class,[
                "attr"=>[
                    "placeholder"=>"Avatar",
                    "class"=>"custom-file-input"
                ]])

            ->add('zone',ChoiceType::class,[
                "choices"=> $zones,
                "placeholder"=>"Choisissez un zone",
                'required' => false,
                'choice_label' => function ($value, $key) {
                    return $value ?: $key;
                },
                'attr'=>['onchange'=>"checkValueZone(this.value)"]
                
                ])
            ->add('zoneField',TextType::class,
                    [
                        'mapped'=>false,
                        'required' => false,
                        'attr'=>
                        [
                        'style'=>'display:none;',
                        'placeholder'=> "Saisisser la zone",]
                        
                        
                    ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Bien::class,
        ]);
    }
}
