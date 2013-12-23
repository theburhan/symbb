<?
namespace SymBB\Core\UserBundle\Twig;

use \SymBB\Core\UserBundle\DependencyInjection\UserAccess;

class AccessExtension extends \Twig_Extension
{
    /**
     *
     * @var UserAccess 
     */
    protected $userAccess;
    
    public function __construct(UserAccess $userAccess) {
        $this->userAccess = $userAccess;
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('hasSymbbAccess', array($this, 'hasSymbbAccess'))
        );
    }
    
    public function hasSymbbAccess($permissionString, $element)
    {
        $this->userAccess->addAccessCheck($permissionString, $element);
        $access             = $this->userAccess->hasAccess();
        return $access;
    }
    

    public function getName()
    {
        return 'symbb_user_access';
    }
}