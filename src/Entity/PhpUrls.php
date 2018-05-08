<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PhpUrlsRepository")
 */
class PhpUrls
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $url;

    /**
     * @ORM\Column(type="integer")
     * @ORM\OneToOne (targetEntity = "PhpSections")
     */
    private $sectionId;

//    /**
//     * @ORM\ManyToOne(targetEntity="PhpSections")
//     * @ORM\JoinColumn(name="section_id", referencedColumnName="id")
//     * @ORM\Column(name="section", type="string")
//     */
//    private $section;
//
//    /**
//     * @return mixed
//     */
//    public function getSection()
//    {
//        return $this->section;
//    }
//
//    /**
//     * @param mixed $section
//     */
//    public function setSection($section)
//    {
//        $this->section = $section;
//    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param mixed $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return mixed
     */
    public function getArticleId()
    {
        return $this->articleId;
    }

    /**
     * @param mixed $articleId
     */
    public function setArticleId($articleId)
    {
        $this->articleId = $articleId;
    }

    /**
     * @return mixed
     */
    public function getSectionId()
    {
        return $this->sectionId;
    }

    /**
     * @param mixed $sectionId
     */
    public function setSectionId($sectionId)
    {
        $this->sectionId = $sectionId;
    }
}
