<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'phone' => fake()->phoneNumber(),
            'address' => fake()->address(),
            'role' => 'customer',
            'is_active' => true,
            'remember_token' => Str::random(10),
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'admin',
        ]);
    }
}
?>
                                <small class="text-muted">Max: {{ $product->stock }} available</small>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Total Price</label>
                                <div class="fw-bold text-primary fs-5" id="total-price">
                                    {{ $product->formatted_price }}
                                </div>
                            </div>

                            <div class="col-md-4">
                                @if($product->isInCart())
                                    <button type="submit" class="btn btn-warning btn-lg w-100">
                                        <i class="fas fa-sync me-2"></i>Update Cart
                                    </button>
                                @else
                                    <button type="submit" class="btn btn-primary btn-lg w-100">
                                        <i class="fas fa-shopping-cart me-2"></i>Add to Cart
                                    </button>
                                @endif
                            </div>
                        </div>
                    </form>
                @else
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        This product is currently out of stock.
                    </div>
                @endif
            </div>
        </div>
