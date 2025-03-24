<?php
class Category {
    private $id;
    private $label;

    public function __construct($id, $label) {
        $this->id = $id;
        $this->label = $label;
    }

    public function getId() {
        return $this->id;
    }

    public function getLabel() {
        return $this->label;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setLabel($label) {
        $this->label = $label;
    }

    public function toArray() {
        return array(
            'id' => $this->id,
            'label' => $this->label
        );
    }

}


