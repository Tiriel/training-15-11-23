<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class Contact
{
    public function __construct(
        #[Assert\NotBlank(message: 'Give your name, punk!')]
        private ?string $name = null,
        #[Assert\Email]
        #[Assert\NotBlank]
        private ?string $email = null,
        #[Assert\NotBlank]
        private ?string $subject = null,
        #[Assert\Length(min: 20)]
        #[Assert\NotBlank]
        private ?string $content = null,
    ) {}

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): Contact
    {
        $this->name = $name;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): Contact
    {
        $this->email = $email;
        return $this;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(?string $subject): Contact
    {
        $this->subject = $subject;
        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): Contact
    {
        $this->content = $content;
        return $this;
    }
}
