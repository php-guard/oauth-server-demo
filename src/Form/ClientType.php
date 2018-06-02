<?php
/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 28/04/2018
 * Time: 16:53
 */

namespace App\Form;


use App\Entity\Client;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;


class ClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('clientName')
            ->add('redirectUris', CollectionType::class, array(
                // each entry in the array will be an "email" field
                'entry_type' => UrlType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                // these options are passed to each "email" type
                'attr' => [
                    'class' => 'collection-type'
                ],
                'entry_options' => array(
                    'attr' => array('class' => 'url-box'),
                ),
            ))
            ->add('type', ChoiceType::class, [
                'choices' => [
                    Client::TYPE_WEB_APPLICATION => Client::TYPE_WEB_APPLICATION,
                    Client::TYPE_USER_AGENT_BASED_APPLICATION => Client::TYPE_USER_AGENT_BASED_APPLICATION,
                    Client::TYPE_NATIVE_APPLICATION => Client::TYPE_NATIVE_APPLICATION
                ]
            ])
            ->add('tokenEndpointAuthMethod', ChoiceType::class, [
                'choices' => [
                    '' => null,
                    'client_secret_basic' => 'client_secret_basic',
                    'client_secret_post' => 'client_secret_post',
                ]
            ])
            ->add('responseTypes', ChoiceType::class, [
                'multiple' => true,
                'choices' => [
                    'code' => 'code',
                    'token' => 'token',
                    'id_token' => 'id_token'
                ]
            ])
            ->add('grantTypes', ChoiceType::class, [
                'multiple' => true,
                'choices' => [
                    'authorization_code' => 'authorization_code',
                    'password' => 'password',
                    'client_credentials' => 'client_credentials',
                    'refresh_token' => 'refresh_token'
                ]
            ])
            ->add('save', SubmitType::class, ['label' => 'Save Client'])
            ->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
                $data = $event->getData();
                $form = $event->getForm();

                if ($data instanceof Client) {
                    if (Client::TYPE_WEB_APPLICATION === $form->get('type')->getData()) {
                        if(empty($data->getPassword())) {
                            $data->setPassword(bin2hex(random_bytes(10)));
                        }
                    }
                    else {
                        $data->setPassword(null);
                    }
                }
            });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
        ]);
    }
}