<?php
namespace Blogger\BlogBundle\Security;
use Blogger\BlogBundle\Entity\User;
use Blogger\BlogBundle\Entity\Album;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;

class AlbumVoter extends Voter

{
    const EDIT = 'edit';
    const DELETE = 'delete';
    private $decisionManager;

    public function __construct(AccessDecisionManagerInterface $decisionManager)
    {
        $this->decisionManager = $decisionManager;
    }

    /**
     * Determines if the attribute and subject are supported by this voter.
     *
     * @param string $attribute An attribute
     * @param mixed $subject The subject to secure, e.g. an object the user wants to access or any other PHP type
     *
     * @return bool True if the attribute and subject are supported, false otherwise
     */
    protected function supports($attribute, $subject)
    {
        //if the attribute isnt one we support, return false
        if (!in_array($attribute, array(self::DELETE, self::EDIT))) {
            return false;
        }

        //only vote on Review objects inside this voter
        if (!$subject instanceof Album) {
            return false;


        }
        return true;
    }

    /**
     * Perform a single access check operation on a given attribute, subject and token.
     * It is safe to assume that $attribute and $subject already passed the "supports()" method check.
     *
     * @param string $attribute
     * @param mixed $subject
     *
     * @return bool
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {

        // ROLE_ADMIN can edit anyones albums
        if ($this->decisionManager->decide($token, array('ROLE_ADMIN'))) {
            return true;
        }

        $user = $token->getUser();

        if (!$user instanceof User){
            //the user must be logged in, if they are not deny their access
            return false;
        }
        //check if the user is the person who left the review
        if ($subject->getCreator() !== $user)
        {
            return false;
        }
        return true;
    }
}
