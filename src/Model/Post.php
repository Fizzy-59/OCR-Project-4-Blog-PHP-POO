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
    private $created_at;
    private $categories = [];

    /**
     * @return mixed
     */
    public function getId()
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
     * @return mixed
     * @throws Exception
     */
    public function getCreatedAt()
    {
        return new DateTime($this->created_at);
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
     */
    public function getSlug()
    {
        return $this->slug;
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
    public function setContent(string $content): self
    {
        $this->content = $content;
        return $this;
    }

}