<?php

namespace App\View;

enum Color: string {
    case RED = "\033[31m";
    case GREEN = "\033[32m";
    case YELLOW = "\033[33m";
    case BLUE = "\033[34m";
    case MAGENTA = "\033[35m";
    case CYAN = "\033[36m";
}

class PrintService {

    private const RESET = "\033[0m";
    private const CHAR_DELAY = 20000;


    /**
     * Prompts the user for input with a colored message, no line break before input.
     * @param string $prompt The prompt message to display.
     * @param Color $color The color to use for the prompt message Cyan will be the default color if none is given.
     * @return string The trimmed user input in lowercase.
     * @throws \RuntimeException If reading from STDIN fails.
     */
    private function requestInput(string $prompt, Color $color = Color::CYAN): string {
        // Print prompt without line break
        $this->colorEcho($prompt, $color, lineBreak: false, isInput: true);
        $input = fgets(STDIN);
        if ($input === false) {
            throw new \RuntimeException("Failed to read input from STDIN.");
        }
        return mb_strtolower(trim($input));
    }

    /**
     * Prompts the user for a non-empty text input, optionally capitalizing the result.
     * @param string $prompt The prompt message to display.
     * @param bool $capitalize If true, capitalize each word in the input.
     * @return string The validated user input.
     */
    public function requestTextInput(string $prompt, bool $capitalize = false): string {
        $input = $this->requestInput($prompt);
        if ($input === '') {
            $this->printMessage("Input cannot be empty.", Color::RED);
            return $this->requestTextInput($prompt);
        }
        return $capitalize ? ucwords($input) : $input;
    }

    /**
     * Prompts the user to select an action from a list (case-insensitive, supports nested arrays).
     * Returns the matched action as defined in the array.
     * @param string $prompt The prompt message to display.
     * @param array $actions List or nested list of valid actions.
     * @return string The selected action from the original array.
     */
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

    /**
     * Prompts the user for a number within a specified range.
     * @param string $prompt The prompt message to display.
     * @param int $min The minimum allowed value (inclusive).
     * @param int $max The maximum allowed value (inclusive).
     * @return int The validated number input.
     */
    public function requestNumberInput(string $prompt, int $min, int $max): int {
        $input = $this->requestTextInput($prompt);
        if (!is_numeric($input)) {
            $this->printMessage("Invalid input: '$input' is not a number.", Color::RED);
            return $this->requestNumberInput($prompt, $min, $max);
        }
        $number = (int)$input;
        if ($number < $min || $number > $max) {
            $this->printMessage("Invalid input: '$input' is not between $min and $max.", Color::RED);
            return $this->requestNumberInput($prompt, $min, $max);
        }
        return $number;
    }


    /**
     * Prints colored text with a typing effect, optional line break, and input prompt support.
     * @param string $text The text to print.
     * @param string $color The ANSI color code to use.
     * @param bool $lineBreak If true, add a line break after the message.
     * @param bool $isInput If true, print an input prompt (": ").
     */
    private function colorEcho(string $text, ?Color $color, bool $lineBreak, bool $isInput = false): void {
        
        $trimmedText = trim($text);
        if ($trimmedText === '') {
            throw new \InvalidArgumentException("Text cannot be empty or whitespace only.");
        }

        $colorCode = $color?->value ?? '';
        $resetCode = $color ? self::RESET : '';

        echo PHP_EOL; // Ensure a new line before printing

        // Print color code
        echo $colorCode;
        // Typing effect per character
        $len = mb_strlen($trimmedText);
        for ($i = 0; $i < $len; $i++) {
            echo mb_substr($trimmedText, $i, 1);
            usleep(self::CHAR_DELAY);
            flush();
        }
        // Input prompt or line break
        if ($isInput) {
            echo ': ';
        }
        // Reset color
        echo $resetCode;
        if ($lineBreak) {
            echo PHP_EOL;
        }
    }

    /**
     * Prints a message with an optional color and line break.
     * @param string $message The message to print.
     * @param Color|null $color The color to use for the message.
     * @param bool $lineBreak If true, add a line break after the message.
     */
    public function printMessage(string $message, ?Color $color = null, bool $lineBreak = false): void {
        $this->colorEcho($message, $color, $lineBreak);
    }

}
