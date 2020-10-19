<?php


namespace App\Entity;


class TrickSearch
{
    /**
     * @var int|null
     */
    private $category;

    /**
     * @return int|null
     */
    public function getCategory(): ?int
    {
        return $this->category;
    }

    /**
     * @param int|null $category
     * @return TrickSearch
     */
    public function setCategory(int $category): TrickSearch
    {
        $this->category = $category;
        return $this;
    }
}