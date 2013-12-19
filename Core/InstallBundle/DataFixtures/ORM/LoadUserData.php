<?
namespace SymBB\Core\InstallBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Acme\HelloBundle\Entity\Group;

class LoadUserGroupData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;
    
    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
    
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        
        $guestName      = $this->container->get('translator')->trans('Guest');
        $userName       = $this->container->get('translator')->trans('User');
        $adminName      = $this->container->get('translator')->trans('Admin');
        
        $userManager    = $this->container->get('fos_user.user_manager');
        
        $userGuest      = $userManager->createUser();
        $userGuest->setLocked(1);
        $userGuest->setUsername($guestName);
        $userGuest->setType('guest');
        $userGuest->addGroup($this->getReference('guest-group'));
        $userGuest->setPassword('guest');
        
        $userUser       = $userManager->createUser();
        $userUser->setUsername($userName);
        $userUser->setType('user');
        $userUser->setPassword('user');
        $userUser->addGroup($this->getReference('user-group'));
        
        $userAdmin      = $userManager->createUser();
        $userAdmin->setUsername($adminName);
        $userAdmin->setType('user'); // admin is also "user" only the group is different
        $userUser->setPassword('admin');
        $userAdmin->addGroup($this->getReference('admin-group'));
       
        $userManager->updateUser($userGuest);
        $userManager->updateUser($userUser);
        $userManager->updateUser($userAdmin);
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 2; // the order in which fixtures will be loaded
    }
}