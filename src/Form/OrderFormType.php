<?php

namespace App\Form;

// use App\Entity\Order;
// use App\Entity\OrderDetails;
// use App\Entity\Product;
// use App\Repository\OrderRepository;  // Add other relevant entities you want to include in the form
// use Symfony\Component\Form\AbstractType;
// use Symfony\Bridge\Doctrine\Form\Type\EntityType;
// use Symfony\Component\Form\Extension\Core\Type\TextType;
// use Symfony\Component\Form\Extension\Core\Type\IntegerType;
// use Symfony\Component\Form\Extension\Core\Type\SubmitType;
// use Symfony\Component\Form\FormBuilderInterface;
// use Symfony\Component\OptionsResolver\OptionsResolver;

// class OrderFormType extends AbstractType
// {
//     public function buildForm(FormBuilderInterface $builder, array $options)
//     {
//         $builder
//             ->add('date', TextType::class)
//             ->add('quantity', IntegerType::class)
//             // Add other fields you want in your order form here
//             ->add('submit', SubmitType::class, ['label' => 'Submit Order']);
//     }

//     public function configureOptions(OptionsResolver $resolver)
//     {
//         $resolver->setDefaults([
//             'data_class' => Order::class,
//         ]);
//     }
// }


use App\Entity\User;
use App\Entity\Order;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class OrderFormType extends AbstractType
{
    private function getPaiementOptions(?User $user): array
    {
        // Vérifier si l'utilisateur est nul
        if ($user === null || $user->getSiret() === null) {
            return [
                'Carte bancaire' => 'carte bancaire',
            ];
        }

        return [
            'Carte bancaire' => 'carte bancaire',
            'Chèque' => 'cheque',
            'Virement bancaire' => 'virement bancaire',
        ];
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $user = $options['user'] ?? null;

        $builder
            ->add(
                'paiement',
                ChoiceType::class,
                [
                    'mapped' => false,
                    'choices' => $this->getPaiementOptions($user),
                ]
            )
            ->add('submit', SubmitType::class, [
                'label' => 'Valider le moyen de paiement',
                'attr' => [
                    'autofocus' => true,
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Order::class,
            'user' => null, // Option personnalisée pour passer l'utilisateur
        ]);
    }
}