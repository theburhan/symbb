<?
/**
*
* @package symBB
* @copyright (c) 2013 Christian Wielath
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/
namespace SymBB\Extension\BBCodeBundle\DependencyInjection; 

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface 
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('sym_bb_extension_bb_code');

        $rootNode
            ->children()
                ->arrayNode('bbcodes') 
                    ->prototype('array')
                        ->prototype('array')
                            ->prototype('array')
                                ->children()
                                    ->scalarNode('code')->isRequired()->end()
                                    ->scalarNode('image')->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}