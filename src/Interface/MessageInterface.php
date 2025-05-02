<?php
namespace App\Interface;
    interface MessageInterface {
        public function getMessage(): ?string;
        public function setMessage(string $message): self;
        public function getLenghtMessage(): int;

    }
?>