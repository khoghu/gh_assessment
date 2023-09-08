<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ParkingSlot>
 */
class ParkingSlotFactory extends Factory
{
    private $letters = ['A', 'B', 'C'];
    private $currentLetterIndex = 0;
    private $currentSlotNumber = 1;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $letter = $this->letters[$this->currentLetterIndex];
        $slotName = $letter . $this->currentSlotNumber;

        $this->currentSlotNumber++;
        if ($this->currentSlotNumber > 5) {
            $this->currentSlotNumber = 1;
            $this->currentLetterIndex++;
        }

        return [
            'slot' => $slotName,
        ];
    }
}
