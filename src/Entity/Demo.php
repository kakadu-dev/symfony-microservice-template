<?php
//
//namespace App\Entity;
//
//use App\Repository\DemoRepository;
//use Doctrine\ORM\Mapping as ORM;
//
///**
// * @ORM\Table(name="countries")
// * @ORM\Entity(repositoryClass=DemoRepository::class)
// */
//class Demo
//{
//    /**
//     * @ORM\Id()
//     * @ORM\GeneratedValue()
//     * @ORM\Column(type="integer")
//     */
//    private int $id;
//
//    /**
//     * @var string
//     *
//     * @ORM\Column(type="string", length=255)
//     */
//    private string $alias = 'by';
//
//    /**
//     * @var string
//     *
//     * @ORM\Column(type="string", length=255)
//     */
//    private string $title;
//
//    /**
//     * @return int|null
//     */
//    public function getId(): ?int
//    {
//        return $this->id;
//    }
//
//    /**
//     * @return string|null
//     */
//    public function getTitle(): ?string
//    {
//        return $this->title;
//    }
//
//    /**
//     * @return string
//     */
//    public function getAlias(): string
//    {
//        return $this->alias;
//    }
//
//    /**
//     * @param string $title
//     *
//     * @return $this
//     */
//    public function setTitle(string $title): self
//    {
//        $this->title = $title;
//
//        return $this;
//    }
//
//    /**
//     * @param string $alias
//     *
//     * @return $this
//     */
//    public function setAlias(string $alias): self
//    {
//        $this->alias = $alias;
//
//        return $this;
//    }
//}
