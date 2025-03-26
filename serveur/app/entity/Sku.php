<?php
class Sku {
    private $label;
    private $stock;

    public function __construct(string $label, int $stock) {
        $this->label = $label;
        $this->stock = $stock;
    }

    public function getLabel(): string {
        return $this->label;
    }

    public function getStock(): int {
        return $this->stock;
    }

    public function setLabel(string $label): void {
        $this->label = $label;
    }

    public function setStock(int $stock): void {
        $this->stock = $stock;
    }

    public function toArray(): array {
        return [
            'label' => $this->label,
            'stock' => $this->stock
        ];
    }

}
