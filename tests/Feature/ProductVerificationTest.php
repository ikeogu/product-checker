<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use App\Models\Product;
use Illuminate\Database\QueryException;

uses(RefreshDatabase::class);

it('verifies product by text', function () {
    // Arrange
    $product = Product::factory()->create([
        'name' => 'Paracetamol',
    ]);

    Http::fake([
        '*' => Http::response(['matched' => true, 'product' => $product->toArray()], 200),
    ]);

    // Act
    $response = $this->postJson(route('products.verify'), [
        'text' => 'Paracetamol',
    ]);

    // Assert
    $response->assertStatus(200);
    $response->assertJson([
        'status' => 'success',
    ]);
});

it('verifies product by barcode', function () {
    // Arrange
    $product = Product::factory()->create([
        'barcode' => '1234567890',
    ]);

    Http::fake([
        '*' => Http::response(['matched' => true, 'product' => $product->toArray()], 200),
    ]);

    // Act
    $response = $this->postJson(route('products.verify'), [
        'barcode' => '1234567890',
    ]);

    // Assert
    $response->assertStatus(200);
    $response->assertJson([
        'status' => 'success',
    ]);
});

it('verifies product by image', function () {
    Storage::fake('public');

    // Arrange: fake uploaded image
    $file = UploadedFile::fake()->image('drug.jpg');

    Http::fake([
        '*' => Http::response([
            'matched' => true,
            'product' => ['name' => 'Amoxicillin', 'barcode' => '987654321']
        ], 200),
    ]);

    Product::factory()->create([
        'image_url' => Storage::url('uploads/products/' . $file->hashName()),
    ]);
    // Act
    $response = $this->postJson(route('products.verify'), [
        'image' => $file,
    ]);


    // Assert
    $response->assertStatus(200);
    $response->assertJson([
        'status' => 'success',
    ]);

    //Storage::disk('public')->assertExists('uploads/products/' . $file->hashName());
});

it('returns error if no input provided', function () {
    $response = $this->postJson(route('products.verify'), []);

    $response->assertStatus(422);
    $response->assertJson([
        'status' => 'error',
    ]);
});


it('returns error if invalid input provided', function () {
    $response = $this->postJson(route('products.verify'), [
        'invalid_field' => 'some_value',
    ]);

    $response->assertStatus(422);
    $response->assertJson([
        'status' => 'error',
        'message' => 'No input provided. Please submit a barcode, text, or image.'
    ]);
});

// 🟢 Edge Case: Barcode must be unique
it('fails when duplicate barcode is inserted [EDGE CASE]', function () {
    $product = Product::factory()->create([
        'barcode' => 'DUPLICATE-123',
    ]);

    $this->expectException(QueryException::class);

    Product::factory()->create([
        'barcode' => 'DUPLICATE-123', // 🚨 duplicate unique field
    ]);
});
