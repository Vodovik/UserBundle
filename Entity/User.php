<?php

namespace UserBundle\Entity;

use CompositionBundle\Entity\Track;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Play\AddressBundle\Entity\Address;
use Play\CoreBundle\Model\CustomerInterface;
use Play\CoreBundle\Model\UserInterface;
use FOS\UserBundle\Model\User as BaseUser;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="UserBundle\Repository\UserRepository")
 */
class User extends BaseUser implements CustomerInterface, UserInterface
{
    use TimestampableEntity;
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="firstname", type="string", length=255, nullable=true)
     */
    private $firstname;

    /**
     * @var string
     *
     * @ORM\Column(name="lastname", type="string", length=255, nullable=true)
     */
    private $lastname;

    /**
     * @var string
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $onlyShowPseudo = false;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="birthday", type="date", nullable=true)
     */
    private $birthday;

    /**
     * @ORM\OneToMany(targetEntity="UserBundle\Entity\UserInstrument", mappedBy="user", cascade={"persist"})
     */
    private $userInstrument;

    /**
     * TODO implement sylius
     */
     //* @ORM\ManyToOne(targetEntity="Play\AddressBundle\Entity\Address")
    private $address;

    /**
     * @ORM\OneToMany(targetEntity="CompositionBundle\Entity\Track", mappedBy="user", cascade={"persist"})
     */
    private $tracks;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    private $biography;

    /**
     * @ORM\Column(name="facebook_id", type="string", length=255, nullable=true)
     */
    private $facebookId;

    /**
     * @var string
     * @ORM\Column(name="gender", type="string", length=1)
     */
    private $gender = CustomerInterface::UNKNOWN_GENDER;

    /**
     * @var bool
     * @ORM\Column(name="subscribed_to_newsletter", type="boolean")
     */
    private $subscribedToNewsletter = true;

    /**
     * @var string
     * @ORM\Column(name="phone_number", type="string", nullable=true)
     */
    private $phoneNumber;

    /**
      @var string $facebookAccessToken */

    private $facebookAccessToken;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="Play\AddressBundle\Entity\Address", mappedBy="user")
     */
    private $addresses;

    /**
     *
     * @ORM\Column(name="credit", type="decimal", precision=10, scale=2)
     * @Assert\Range(min=0)
     */
    private $credit;

    /**
     * @var \DateTime
     * @ORM\Column(name="premium", type="date", nullable=true)
     */
    private $premiumEnd;

    /**
     * User constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->tracks = new ArrayCollection();
        $this->userInstrument = new ArrayCollection();
        $this->addresses = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set firstname
     *
     * @param string $firstname
     *
     * @return User
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get firstname
     *
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     *
     * @return User
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname
     *
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * @return string
     */
    public function getName($forceName = false)
    {
        if ($this->onlyShowPseudo && !$forceName) {
            return $this->getUsername();
        }
        return $this->getFirstname() . ' ' . $this->getLastname();
    }

    /**
     * @return string
     */
    public function getFullName()
    {
        return $this->getName();
    }

    /**
     * Set birthday
     *
     * @param \DateTime $birthday
     *
     * @return User
     */
    public function setBirthday(\DateTime $birthday = null)
    {
        $this->birthday = $birthday;

        return $this;
    }

    /**
     * Get birthday
     *
     * @return \DateTime
     */
    public function getBirthday()
    {
        return $this->birthday;
    }


    /**
     * @param string $facebookId
     * @return User
     */
    public function setFacebookId($facebookId)
    {
        $this->facebookId = $facebookId;

        return $this;
    }

    /**
     * @return string
     */
    public function getFacebookId()
    {
        return $this->facebookId;
    }

    /**
     * @param string $facebookAccessToken
     * @return User
     */
    public function setFacebookAccessToken($facebookAccessToken)
    {
        $this->facebookAccessToken = $facebookAccessToken;

        return $this;
    }

    /**
     * @return string
     */
    public function getFacebookAccessToken()
    {
        return $this->facebookAccessToken;
    }

    /**
     * Add userInstrument
     *
     * @param UserInstrument $userInstrument
     *
     * @return User
     */
    public function addUserInstrument(UserInstrument $userInstrument)
    {
        $this->userInstrument[] = $userInstrument;

        return $this;
    }

    /**
     * Remove userInstrument
     *
     * @param UserInstrument $userInstrument
     */
    public function removeUserInstrument(UserInstrument $userInstrument)
    {
        $this->userInstrument->removeElement($userInstrument);
    }

    /**
     * Get userInstrument
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUserInstrument()
    {
        return $this->userInstrument;
    }

    /**
     * Add track
     *
     * @param Track $track
     *
     * @return User
     */
    public function addTrack(Track $track)
    {
        $this->tracks[] = $track;
        $track->setUser($this);

        return $this;
    }

    /**
     * Remove track
     *
     * @param Track $track
     */
    public function removeTrack(Track $track)
    {
        $this->tracks->removeElement($track);
    }

    /**
     * Get tracks
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTracks()
    {
        return $this->tracks;
    }

    /**
     * Set biography
     *
     * @param string $biography
     *
     * @return User
     */
    public function setBiography($biography)
    {
        $this->biography = $biography;

        return $this;
    }

    /**
     * Get biography
     *
     * @return string
     */
    public function getBiography()
    {
        return $this->biography;
    }

    /**
     * @return mixed
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * @param mixed $gender
     * @return User
     */
    public function setGender($gender)
    {
        $this->gender = $gender;
        return $this;
    }

    /**
     * @return mixed
     */
    public function IsSubscribedToNewsletter()
    {
        return $this->subscribedToNewsletter;
    }

    /**
     * @param bool $subscribedToNewsletter
     */
    public function setSubscribedToNewsletter($subscribedToNewsletter)
    {
        // TODO: Implement setSubscribedToNewsletter() method.
    }

    /**
     * @return mixed
     */
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    /**
     * @param mixed $phoneNumber
     * @return User
     */
    public function setPhoneNumber($phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;
        return $this;
    }

    public function isMale()
    {
        return $this->getGender() === '1';
    }

    public function isFemale()
    {
        return $this->getGender() === '2';
    }

    /**
     * Set credit
     *
     * @param string $credit
     *
     * @return User
     */
    public function setCredit($credit)
    {
        $this->credit = $credit;

        return $this;
    }

    /**
     * Get credit
     *
     * @return string
     */
    public function getCredit()
    {
        return $this->credit;
    }

    /**
     * Set premiumEnd
     *
     * @param \DateTime $premiumEnd
     *
     * @return User
     */
    public function setPremiumEnd($premiumEnd)
    {
        $this->premiumEnd = $premiumEnd;

        return $this;
    }

    /**
     * Get premiumEnd
     *
     * @return \DateTime
     */
    public function getPremiumEnd()
    {
        return $this->premiumEnd;
    }

    /**
     * Is premiumEnd
     *
     * @return bool
     */
    public function isPremium()
    {
        return ($this->getPremiumEnd() >= new \DateTime());
    }

    /**
     * Set onlyShowPseudo
     *
     * @param boolean $onlyShowPseudo
     *
     * @return User
     */
    public function setOnlyShowPseudo($onlyShowPseudo)
    {
        $this->onlyShowPseudo = $onlyShowPseudo;

        return $this;
    }

    /**
     * Get onlyShowPseudo
     *
     * @return boolean
     */
    public function getOnlyShowPseudo()
    {
        return $this->onlyShowPseudo;
    }

    /**
     * Get subscribedToNewsletter
     *
     * @return boolean
     */
    public function getSubscribedToNewsletter()
    {
        return $this->subscribedToNewsletter;
    }

    /**
     * Add address
     *
     * @param Address $address
     *
     * @return User
     */
    public function addAddress(Address $address)
    {
        $this->addresses[] = $address;

        return $this;
    }

    /**
     * Remove address
     *
     * @param Address $address
     * @return User
     */
    public function removeAddress(Address $address)
    {
        $this->addresses->removeElement($address);

        return $this;
    }

    /**
     * Get addresses
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAddresses()
    {
        return $this->addresses;
    }
}
