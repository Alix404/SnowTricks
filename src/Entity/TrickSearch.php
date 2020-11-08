<?php


namespace App\Entity;


class TrickSearch
{
    /**
     * @var Category|null
     */
    private $category;

    /**
     * @return Category|null
     */
    public function getCategory(): ?Category
    {
        return $this->category;
    }

    /**
     * @param Category|null $category
     * @return TrickSearch
     */
    public function setCategory(Category $category): TrickSearch
    {
        $this->category = $category;
        return $this;
    }
}