<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use ApiPlatform\Core\Annotation\ApiResource;
use Lexik\Bundle\JWTAuthenticationBundle\Security\User\JWTUserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 */
// #[ApiResource(
//     itemOperations: [
//         'get' => [
//             'controller' => NotFoundAction::class,
//             'read' => false,  
//             'output' => false      ]
//     ],
//     'me': [
//        'pagination_enabled' => false,
//        'path' => '/me'
//         'method' => 'get',
//         'controller' => MeController::class,
//         'read' => false,
//         'openapi_context' => [
//             'security' => [['bearerAuth' => []]]
//         ]
//     ]
// )]
#[ApiResource(
   
    )]
class User implements JWTUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    #[Groups(['read:User:collection'])]
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    #[Groups(['read:User:item','write:User:item'])]
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Email()
     */
    #[Groups(['read:User:item','write:User:item'])]
    private $email;


    /**
     * @ORM\Column(type="json")
     */
    #[Groups(['read:User:item','write:User:item'])]
    private $roles = ["ROLE_USER"];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    #[Groups(['write:User:item'])]
    private $password;

    public function __construct()
    {
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): ?self
    {
        $this->id = $id;
        return $this;
    }
    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }


    public static function createFromPayload($id, array $payload) {
        $user = (new User())->setId($id);
        return $user;
    }
}
