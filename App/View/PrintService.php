<?php

namespace App\View;

class PrintService {

    // ANSI color codes for terminal
    private $colors = [
        'red' => "\033[31m",
        'green' => "\033[32m",
        'yellow' => "\033[33m",
        'blue' => "\033[34m",
        'magenta' => "\033[35m",
        'cyan' => "\033[36m",
        'reset' => "\033[0m"
    ];

    /**
     * Prompts the user for input with a CYAN colored message, no line break before input.
     * @param string $prompt The prompt message to display.
     * @return string The trimmed user input.
     */
    private function requestInput(string $prompt): string {
        // Print prompt without line break
        $this->colorEcho($prompt, 'cyan', lineBreak: false, isInput: true);
        $input = fgets(STDIN);
        if ($input === false) {
            throw new \RuntimeException("Failed to read input from STDIN.");
        }
        return mb_strtolower(trim($input));
    }

    public function requestTextInput(string $prompt, bool $capitalize = false): string {
        $input = $this->requestInput($prompt);
        if ($input === '') {
            $this->printRedMessage("Input cannot be empty.");
            return $this->requestTextInput($prompt);
        }
        return $capitalize ? ucwords($input) : $input;
    }

    public function requestActionInput(string $prompt, array $actions): string {
        // Flatten actions array if needed
        $flatActions = [];
        foreach ($actions as $action) {
            if (is_array($action)) {
                foreach ($action as $a) {
                    $flatActions[] = $a;
                }
            } else {
                $flatActions[] = $action;
            }
        }

        $input = $this->requestTextInput($prompt);

        // Find a matching action (case-insensitive), return the original action
        foreach ($flatActions as $action) {
            if (mb_strtolower($action) === $input) {
                return $action;
            }
        }

        return $input;
    }

    public function requestNumberInput(string $prompt, int $min, int $max): int {
        $input = $this->requestTextInput($prompt);
        if (!is_numeric($input)) {
            $this->printRedMessage("Invalid input: '$input' is not a number.");
            return $this->requestNumberInput($prompt, $min, $max);
        }
        $number = (int)$input;
        if ($number < $min || $number > $max) {
            $this->printRedMessage("Invalid input: '$input' is not between $min and $max.");
            return $this->requestNumberInput($prompt, $min, $max);
        }
        return $number;
    }

    /**
     * Print colored text with a typing effect, optional line break, and input prompt support.
     * @param string $text
     * @param string $color
     * @param bool $lineBreak
     * @param bool $isInput
     * @param int $charDelay Typing delay in microseconds per character (default 30000 = 30ms)
     */
    private function colorEcho(string $text, string $color, bool $lineBreak, bool $isInput = false, int $charDelay = 30000): void {
        if ($color === 'reset') {
            throw new \InvalidArgumentException("'reset' is not a valid color for output.");
        }
        if (!array_key_exists($color, $this->colors)) {
            throw new \InvalidArgumentException("Color '$color' is not defined.");
        }
        $trimmedText = trim($text);
        if ($trimmedText === '') {
            throw new \InvalidArgumentException("Text cannot be empty or whitespace only.");
        }
        $colorValue = $this->colors[$color];
        $resetValue = $this->colors['reset'];

        echo PHP_EOL; // Ensure a new line before printing

        // Print color code
        echo $colorValue;
        // Typing effect per character
        $len = mb_strlen($trimmedText);
        for ($i = 0; $i < $len; $i++) {
            echo mb_substr($trimmedText, $i, 1);
            usleep($charDelay);
            flush();
        }
        // Reset color
        echo $resetValue;
        // Input prompt or line break
        if ($isInput) {
            echo ': ';
        }
        if ($lineBreak) {
            echo PHP_EOL;
        }
    }

    public function printRedMessage(string $message, bool $lineBreak = false): void {
        $this->colorEcho($message, 'red', $lineBreak);
    }

    public function printGreenMessage(string $message, bool $lineBreak = false): void {
        $this->colorEcho($message, 'green', $lineBreak);
    }

    public function printBlueMessage(string $message, bool $lineBreak = false): void {
        $this->colorEcho($message, 'blue', $lineBreak);
    }

    public function printMagentaMessage(string $message, bool $lineBreak = false): void {
        $this->colorEcho($message, 'magenta', $lineBreak);
    }

    public function printCyanMessage(string $message, bool $lineBreak = false): void {
        $this->colorEcho($message, 'cyan', $lineBreak);
    }

    public function printYellowMessage(string $message, bool $lineBreak = false): void {
        $this->colorEcho($message, 'yellow', $lineBreak);
    }

}
