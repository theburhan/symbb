<?php
/**
*
* @package symBB
* @copyright (c) 2013 Christian Wielath
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/
namespace SymBB\Core\ConfigBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;

class SymBBCoreConfigExtension extends Extension implements PrependExtensionInterface 
{
    
    public function prepend(ContainerBuilder $container)
    {
        
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
        $loader->load('doctrine.yml');
        $loader->load('twig.yml');
        $loader->load('symbb.yml');
        $loader->load('fos_user.yml');
        $loader->load('fos_rest.yml');
        $loader->load('fos_messages.yml');
        $loader->load('knp.yml');
        $loader->load('lsw_memcache.yml');
        $loader->load('swiftmailer.yml');
        $loader->load('framework.yml');

    }
        
    public function load(array $configs, ContainerBuilder $container)
    {        
        
        $config = array();
        // reverse array
        $configs = array_reverse($configs);
        foreach ($configs as $subConfig) {
            $config = array_merge($config, $subConfig);
        }
      
        $configuration = new Configuration();
        $config        = $this->processConfiguration($configuration, array($config));
        
        $container->setParameter('symbb_config', $config);
        $container->setParameter('symbb_system_name', $config['system']['name']);
        $container->setParameter('symbb_system_email', $config['system']['email']);
        $container->setParameter('twig.globals.symbb_config.template', $config['template']);
        $container->setParameter('twig.globals.symbb_config', $config);
        
    }
}
