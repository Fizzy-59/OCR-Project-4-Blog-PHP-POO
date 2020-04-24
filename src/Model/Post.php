<?php

namespace App\Model;

use App\Helpers\Text;
use DateTime;
use Exception;

class Post
{
    private $id;
    private $slug;
    private $name;
    private $content;
    private $categories = [];
    private $created_at;



    /**
     * @return mixed
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getFormattedContent()
    {
        return nl2br( htmlentities($this->content) );
    }


    /**
     * @return Category[]
     */
    public function getCategories(): array
    {
        return $this->categories;
    }

    /**
     * Make an abstract for content of PostTable
     *
     * @return String
     */
    public function getExcerpt () : ?String
    {
        if($this->content === null)
        {
            return null;
        }
        return nl2br( htmlentities(Text::excerpt($this->content, 60)) ) ;
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function getCreatedAt()
    {
        return new DateTime($this->created_at);
    }


    public function setCreatedAt($date)
    {
        $this->created_at = $date;
        return $this;
    }

    public function getSlug(): ?String
    {
        return $this->slug;
    }


    public function setSlug(string $slug): self
    {
        $this->slug = $slug;
        return $this;
    }


    public function addCategory(Category $category): void
    {
        $this->categories[] = $category;
        $category->setPost($this);
    }

    /**
     * @param mixed $name
     */
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param mixed $content
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * @param mixed $content
     */
    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }


}