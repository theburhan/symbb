<?php
/**
*
* @package symBB
* @copyright (c) 2013 Christian Wielath
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace SymBB\Core\UserBundle\Entity\User;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="user_data")
 */
class Data
{
    
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @ORM\OneToOne(targetEntity="SymBB\Core\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="cascade")
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $signature;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $avatar;


    ############################################################################
    # Default Get and Set
    ############################################################################
    public function getSignature(){return (string)$this->signature;}
    public function setSignature($value){ $this->signature = $value;}
    public function getAvatar(){return (string)$this->avatar;}
    public function setAvatar($value){ $this->avatar = $value;}
    public function getUser(){return $this->user;}
    public function setUser($value){ $this->user = $value;}
    ############################################################################
    
    public function hasSignature(){
        $sig = $this->getSignature();
        if(empty($sig)){
            return false;
        }
        return true;
    }
    
}