<?php
/**
*
* @package symBB
* @copyright (c) 2013 Christian Wielath
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace SymBB\Core\ForumBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="forum_topics")
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks()
 */
class Topic
{
    /**
     * @ORM\Column(type="integer", unique=true)
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @ORM\Column(type="datetime")
     */
    private $changed;

    /**
     * @ORM\ManyToOne(targetEntity="SymBB\Core\ForumBundle\Entity\Post")
     * @ORM\JoinColumn(referencedColumnName="id", onDelete="CASCADE")
     */
    private $mainPost;
    
    /**
     * @ORM\OneToMany(targetEntity="SymBB\Core\ForumBundle\Entity\Post", mappedBy="topic")
     * @ORM\OrderBy({"changed" = "ASC", "created" = "ASC"})
     */
    private $posts;
    
    /**
     * @ORM\OneToMany(targetEntity="SymBB\Core\ForumBundle\Entity\Topic\Flag", mappedBy="topic")
     */
    private $flags;

    /**
     * @ORM\ManyToOne(targetEntity="SymBB\Core\ForumBundle\Entity\Forum", inversedBy="topics")
     * @ORM\JoinColumn(name="forum_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $forum;

    /**
     * @ORM\ManyToOne(targetEntity="SymBB\Core\UserBundle\Entity\User", inversedBy="topics")
     * @ORM\JoinColumn(name="author_id", referencedColumnName="id", onDelete="NO ACTION")
     */
    private $author;

    /**
     * @ORM\Column(type="boolean")
     */
    private $locked = false;


    public function __construct() {
        $this->posts = new ArrayCollection();
    }

    ############################################################################
    # Default Get and Set
    ############################################################################
    public function getId(){return (int)$this->id;}
    public function setId($value){$this->id = $value;}
    public function setForum($object){$this->forum = $object;}
    /**
     * 
     * @return Forum
     */
    public function getForum(){return $this->forum;}
    public function setAuthor($object){$this->author = $object;}
    public function getAuthor(){return $this->author;}
    public function getPosts(){return $this->posts;}
    public function getFlags(){return $this->flags;}
    public function getCreated(){return $this->created;}
    public function getChanged(){return $this->changed;}
    public function setLocked($value){$this->locked = $value;}
    public function isLocked(){return $this->locked;}
    public function setMainPost($object){$this->mainPost = $object;}
    public function getMainPost(){return $this->mainPost;}
    ############################################################################
    
    /**
    * @ORM\PrePersist
    */
    public function setCreatedValue()
    {
      $this->created = new \DateTime();
    }

    /**
    * @ORM\PrePersist
    * @ORM\PreUpdate
    */
    public function setChangedValue()
    {
      $this->changed = new \DateTime();
    }
    
    
    public function getName(){
        $name = '';
        $post  = $this->getMainPost();
        if(is_object($post)){
            $name = $post->getName();
        }
        return $name;
       
    }
    public function setName($value){
        $post  = $this->getMainPost();
        if(!is_object($post)){
            $post = new Post();
            $post->setTopic($this);
        }
        $post->setName($value);
    }
    
    public function hasPosts(){
        $posts = $this->getPosts();
        if($posts->count() > 0){
            return true;
        }
        return false;
    }
    
    public function getSeoName(){
        $name = $this->getName();
        $name = preg_replace('/\W+/', '-', $name);
        $name = strtolower(trim($name, '-'));
        return $name;
    }
    
    public function getLastPost(){
        return $this->getPosts()->last();
    }
    
    public function getPostCount(){
        $count = $this->getPosts()->count() - 1;
        return $count;
    }
    
    public function addPost($post){
        $post->setTopic($this);
        $this->posts->add($post);
    }
    
    public function getParent(){
        return $this->getForum();
    }
}