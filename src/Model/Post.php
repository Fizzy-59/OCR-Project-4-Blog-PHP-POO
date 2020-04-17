<?php

namespace App\Model;

use App\Helpers\Text;
use DateTime;

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
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function getCreatedAt()
    {
        return new DateTime($this->created_at);
    }

    /**
     * @return array
     */
    public function getCategories(): array
    {
        return $this->categories;
    }

    /**
     * Make an abstract for content of Post
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

}