<?php

namespace Database\Factories;

use App\Enums\ConversationSource;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Conversation>
 */
class ConversationFactory extends Factory
{
    public function definition(): array
    {
        $converted = fake()->boolean(40);

        return [
            'caller_name' => fake()->name(),
            'phone_number' => fake()->phoneNumber(),
            'source' => fake()->randomElement(ConversationSource::cases()),
            'transcript' => fake()->paragraphs(3, true),
            'converted' => $converted,
            'conversion_reason' => $converted ? fake()->sentence() : null,
            'duration_seconds' => fake()->numberBetween(30, 600),
        ];
    }

    public function converted(): static
    {
        return $this->state(fn (array $attributes) => [
            'converted' => true,
            'conversion_reason' => fake()->sentence(),
        ]);
    }

    public function notConverted(): static
    {
        return $this->state(fn (array $attributes) => [
            'converted' => false,
            'conversion_reason' => null,
        ]);
    }

    public function fromSource(ConversationSource $source): static
    {
        return $this->state(fn (array $attributes) => [
            'source' => $source,
        ]);
    }
}
