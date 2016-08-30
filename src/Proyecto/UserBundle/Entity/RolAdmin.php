<?php

namespace Proyecto\UserBundle\Entity;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;
 
class RolAdmin extends Admin
{
    /**
     * Configuración de los campos del formulario
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('name')
            // ->add('roles', 'sonata_security_roles', array(
            //     'expanded' => true,
            //     'multiple' => true,
            //     'required' => false
            // ))
        ;
    }

    /**
     * Configuración de los campos de los filtros de la grid
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('name')
            ->add('roles')
        ;
    }
    /**
     * Configuración de las columnas de la grid
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id')
            ->add('name')
            ->add('roles','array')
        ;
    }
}