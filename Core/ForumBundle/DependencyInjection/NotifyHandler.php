<?
/**
*
* @package symBB
* @copyright (c) 2013 Christian Wielath
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace SymBB\Core\ForumBundle\DependencyInjection;

use \SymBB\Core\UserBundle\Entity\UserInterface;
use \SymBB\Core\ForumBundle\Entity\Topic;
use \Symfony\Component\Security\Core\SecurityContextInterface;
use \Doctrine\ORM\EntityManager;

class NotifyHandler
{
    /**
     *
     * @var EntityManager
     */
    protected $em;
    
    /**
     *
     * @var TopicFlagHandler
     */
    protected $flagHandler;
    
    /**
     *
     * @var SecurityContextInterface
     */
    protected $securityContext;

    /**
     *
     * @var UserInterface
     */
    protected $user;

    protected $mailer;
    protected $container;
    
    protected $config = array();
    protected $locale = 'en';

    public function __construct($container) {
        $this->em               = $container->get('doctrine.orm.symbb_entity_manager');
        $this->securityContext  = $container->get('security.context');
        $this->flagHandler      = $container->get('symbb.core.topic.flag');
        $this->mailer           = $container->get('swiftmailer.mailer.default');
        $this->translator       = $container->get('translator');
        $this->config           = $container->getParameter('symbb_config');
        $this->locale           = $container->get('request')->getLocale();
        $this->container        = $container;
        if(strpos('_', $this->locale) !== false){
            $this->locale = explode('_', $this->locale);
            $this->locale = reset($this->locale);
        }
    }
    
    public function getUser(){
        if(!is_object($this->user)){
            $this->user = $this->securityContext->getToken()->getUser();
        }
        return $this->user;
    }
    
    public function sendTopicNotifications(Topic $topic, $user){
        
        if(is_numeric($user)){
            $user = $this->em->getRepository('SymBBCoreUserBundle:User')->find($user);
        }
        
        $subject    = $this->translator->trans('It was written a new answer to "%topic%"', array('%topic%' => $topic->getName()), 'symbb_email');
        $sender     = $this->config['system']['email'];
        $recipient  = $user->getEmail();
        
        $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom($sender)
            ->setTo($recipient)
            ->setBody(
                $this->container->get('twig')->render(
                    $this->config['template']['email'].':Email:topic_notify.'.$this->locale.'.html.twig',
                    array('topic' => $topic, 'user' => $user, 'symbb_config' => $this->config)
                ), 'text/html'
            )
        ;
        
        $this->mailer->send($message);
    }
}
