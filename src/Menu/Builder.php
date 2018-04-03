<?php
// src/Menu/Builder.php
namespace App\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class Builder
{
    private $factory;

    /**
     * @param FactoryInterface $factory
     */
    public function __construct(FactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    public function createMainMenu(RequestStack $requestStack)
    {
        $menu = $this->factory->createItem('root');

        $menu->setChildrenAttribute('class', 'navbar-nav mr-auto');

        $menu->addChild('Home', array('route' => 'app_home'));
        $menu->addChild('Meetings', array('route' => 'app_meetings'));
        

        return $menu;
    }
    
    public function createUserMenu(RequestStack $requestStack, AuthorizationCheckerInterface $authChecker)
    {
        $menu = $this->factory->createItem('root');

        $menu->setChildrenAttribute('class', 'navbar-nav ml-auto');

        if ($authChecker->isGranted('ROLE_USER'))
        {
            $menu->addChild('Log out', array('route' => 'fos_user_security_logout'));
        }
        else
        {
            $menu->addChild('Log in', array('route' => 'fos_user_security_login'));
            $menu->addChild('Register', array('route' => 'fos_user_registration_register'));
        }

        return $menu;
    }
}