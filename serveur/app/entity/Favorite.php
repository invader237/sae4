<?php
class Favorite{
    private $id_utilisateur;
    private array $favoris=[];

    public function __construct($id_utilisateur,$favoris=[]){
        $this->id_utilisateur=$id_utilisateur;
        $this->favoris=$favoris;
    }

    public function getIdUtilisateur(){
        return $this->id_utilisateur;
    }
    public function getFavoris(){
        return $this->favoris;
    }

    public function setIdUtilisateur($id_utilisateur){
        $this->id_utilisateur=$id_utilisateur;
    }
    public function setFavoris($favoris){
        $this->favoris=$favoris;
    }

    public function toArray(): array{
        $favorisArray = [];

        foreach ($this->favoris as $favori) {
            $favorisArray[] = [
                'product' => $favori->toArray() // Appelle la méthode toArray() de ProductDetail
            ];
        }

        return [
            "id_utilisateur" => $this->id_utilisateur,
            "favoris" => $favorisArray
        ];
    }
}
?>