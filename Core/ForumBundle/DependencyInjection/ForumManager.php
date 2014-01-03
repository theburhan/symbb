<?
/**
*
* @package symBB
* @copyright (c) 2013 Christian Wielath
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace SymBB\Core\ForumBundle\DependencyInjection;

use \SymBB\Core\ForumBundle\Entity\Forum;
use \Symfony\Component\Security\Core\SecurityContextInterface;
use SymBB\Core\ForumBundle\DependencyInjection\TopicFlagHandler;
use SymBB\Core\ForumBundle\DependencyInjection\PostFlagHandler;

class ForumManager
{
    
    /**
     *
     * @var SecurityContextInterface 
     */
    protected $securityContext;
    /**
     *
     * @var TopicFlagHandler
     */
    protected $topicFlagHandler;
    /**
     *
     * @var PostFlagHandler
     */
    protected $postFlagHandler;
    
    public function __construct(SecurityContextInterface $securityContext, TopicFlagHandler $topicFlagHandler, PostFlagHandler $postFlagHandler)
    {
        $this->securityContext  = $securityContext;
        $this->topicFlagHandler = $topicFlagHandler;
        $this->postFlagHandler = $postFlagHandler;
    }
    
    public function getUser(){
        if(!is_object($this->user)){
            $this->user = $this->securityContext->getToken()->getUser();
        }
        return $this->user;
    }
    
    public function findNewestTopics(Forum $parent = null)
    {
        $topics = $this->topicFlagHandler->findTopicsByFlag('new', $parent);
        return $topics;
    }
    
    public function findNewestPosts(Forum $parent = null, $limit = null)
    {
        $posts = $this->postFlagHandler->findPostsByFlag('new', $parent, null, true, $limit);
        return $posts;
    }
    
}
