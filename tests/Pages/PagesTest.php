<?php
namespace Pages;

class PagesTest extends \TestCase
{
    /** @test */
    public function viewHomepage()
    {
        $this->visit('/')
            ->see('Welcome to Horse Stories');
    }

    /** @test */
    public function visitRegisterPage()
    {
        $this->visit('/')
            ->click('Sign Up')
            ->seePageIs('/register');
    }

    /** @test */
    public function visitLoginPage()
    {
        $this->visit('/')
            ->click('Sign In')
            ->seePageIs('/login');
    }
}
