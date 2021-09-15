<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\PostRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Valid;

//read:collection pour lire une collection d'article
//read:item correspond à la lecture d'un seul item
/*ici je souhaite afficher les attributs title et slug quand je demande une collection d'article mais afficher 
    mais afficher en plus le contennu ainsi que la date quand je fais une requete d'un seul article
*/

/**
 * @ORM\Entity(repositoryClass=PostRepository::class)
 */
#[ApiResource(
    normalizationContext:['groups'=> ['read:collection']],
    denormalizationContext:['groups'=> ['write:Post']],
    collectionOperations:[
        'get',
        'post' => [
            'validation_groups'=>[Post::class, 'validationGroups']
        ]
    ],
    itemOperations:[
        'get' => [
            'normalization_context' => ['groups'=> ['read:collection', 'read:item', 'read:Post']],
        'put' => [
            'denormalization_context' => ['groups' => ['put:Post']],
            ],
        ],
    ],
)]
class Post
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    #[Groups(['read:collection'])]
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[
        Groups(['read:collection','put:Post']),
        Length(min:5, max:255),

    ]
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[
    Groups(['read:collection','put:Post']),
    Length(min:5, max:255),
    ]
    private $slug;

    /**
     * @ORM\Column(type="text")
     */
    #[Groups(['read:item','put:Post'])]
    private $content;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    #[Groups(['read:item','put:Post'])]
    private $uptdatedAt;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="posts", cascade={"persist"})
     */
    #[
        Groups(['read:item','write:Post']),
        Valid()
    ]
    private $category;

    public static function validationGroups(self $post){
        return['create:Post'];
    }

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->uptdatedAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getUptdatedAt()
    {
        return $this->uptdatedAt;
    }

    public function setUptdatedAt(?\DateTimeImmutable $uptdatedAt): self
    {
        $this->uptdatedAt = $uptdatedAt;

        return $this;
    }

    public function getCategory(): ?category
    {
        return $this->category;
    }

    public function setCategory(?category $category): self
    {
        $this->category = $category;

        return $this;
    }
}
