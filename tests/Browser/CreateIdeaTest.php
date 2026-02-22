<?php

use App\Models\User;

it('create new idea', function () {
    $user = User::factory()->create();
    $title = 'Some Example Title';
    $description = 'An example description';

    $this->actingAs($user);

    visit('/idea')
        ->click('@create-idea-button')
        ->fill('title', $title)
        ->click('@button-status-completed')
        ->fill('description', $description)
        ->fill('@new-link', 'https://ncrousset.dev')
        ->click('@submit-new-link-button')
        ->fill('@new-link', 'https://ncrousset2.dev')
        ->click('@submit-new-link-button')
        ->click('Create')
        ->assertPathIs('/idea');

    expect($user->ideas()->first())->toMatchArray([
        'title' => $title,
        'status' => 'completed',
        'description' => $description,
        'links' => ['https://ncrousset.dev', 'https://ncrousset2.dev'],
    ]);
});

it('closes the create idea modal when cancel is clicked', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    visit('/idea')
        ->click('@create-idea-button')
        ->assertVisible('[aria-labelledby="modal-create-idea-title"]')
        ->click('Cancel')
        ->wait(0.3)
        ->assertAttribute('[aria-labelledby="modal-create-idea-title"]', 'aria-hidden', 'true');
});
