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
    private $image;
    private $oldImage;
    private $pendingUpload = false;

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

    public function getCategoriesIds(): array
    {
        $ids = [];
        foreach ($this->categories as $category)
        {
            $ids[] = $category->getId();
        }
        return $ids;
    }

    /**
     * @param  array $categories
     * @return Post
     */
    public function setCategories(array $categories): self
    {
        $this->categories = $categories;
        return $this;
    }

    public function getImageURL(string $format): ?string
    {
        if(empty($this->image))
        {
            return null;
        }
        return '/posts/' . $this->image . '_' . $format . '.jpg';
    }

    /**
     * @param mixed $image
     * @return Post
     */
    public function setImage($image)
    {
        if(is_array($image) && !empty($image['tmp_name']))
        {
            if (!empty($this->image))
            {
                $this->oldImage = $this->image;
            }
            $this->pendingUpload = true;
            $this->image = $image['tmp_name'];
        }
        if(is_string($image) && !empty($image))
        {
            $this->image = $image;
        }
    }

    /**
     * @return mixed
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param mixed $oldImage
     * @return Post
     */
    public function setOldImage($oldImage)
    {
        $this->oldImage = $oldImage;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOldImage(): ?string
    {
        return $this->oldImage;
    }

    public function shouldUpload(): bool
    {
        return $this->pendingUpload;
    }
}