<?php 

namespace App\Entity;


class TestEntity
{
     /**
     * @var int
     */
     private int $id;

    /**
    * @var 
    */
    private  $newField;

    /**
    * @var string|null
    */
    private ?string $slug = null;

    public function getId(): ?int
    {
        return $this->id;
    }
    
    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function getNewField(): 
    {
        return $this->newField;
    }

    public function setNewField( $newField)
    {
        $this->newField = $newField;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug)
    {
        $this->slug = $slug;
    }

}