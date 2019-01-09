<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;


class UpdatePassword
{

    private $oldPassword;

    /**
     * password length
     *
     * @Assert\Length(min=6, minMessage="Your Password Must be At least 6 chars")
     */
    private $newPassword;

    /**
     *
     * @Assert\EqualTo(propertyPath="newPassword", message="Your Password must be typical")
     */
    private $confirmNewPassword;

    public function getOldPassword(): ?string
    {
        return $this->oldPassword;
    }

    public function setOldPassword(string $oldPassword): self
    {
        $this->oldPassword = $oldPassword;

        return $this;
    }

    public function getNewPassword(): ?string
    {
        return $this->newPassword;
    }

    public function setNewPassword(string $newPassword): self
    {
        $this->newPassword = $newPassword;

        return $this;
    }

    public function getConfirmNewPassword(): ?string
    {
        return $this->confirmNewPassword;
    }

    public function setConfirmNewPassword(string $confirmNewPassword): self
    {
        $this->confirmNewPassword = $confirmNewPassword;

        return $this;
    }
}
