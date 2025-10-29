<?php

namespace iutnc\deefy\audio\lists;

use iutnc\deefy\exception\InvalidPropertyNameException;

class AudioList
{

    protected string $nom;
    protected int $nbrPistes, $dureeTotale;
    protected array $tabPistes;
    protected ? int $id;


    public function __construct(string $n,array $tab = [], ?int $id = null){
        $this->nom = $n;
        $this->tabPistes = $tab;
        $this->updateData();
        $this->id = $id;
    }

    /**
     * @throws InvalidPropertyNameException
     */
    public function __GET(string $at)
    {
        if(property_exists($this,$at)){
            return $this->$at;
        } else
            throw new InvalidPropertyNameException();
    }

    public function updateData(): void
    {
        $dureeTotale = 0;
        foreach ($this->tabPistes as $value){
            $dureeTotale += $value->__GET("duree");
        }
        $this->dureeTotale = $dureeTotale;
        $this->nbrPistes = sizeof($this->tabPistes);
    }

    public function setId(int $n): void
    {
        $this->id = $n;
    }


}