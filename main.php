<?php

if (!isset($argv[1])) {
    echo "please provide bot command...";
    return;
}

class MaqeBotDirection {
    const North = "North";
    const East = "East";
    const West = "West";
    const South = "South";

    static function getMovement($currentDirection, $action) {
        if ($action === MaqeBotAction::Walk)
            return false;

        if ($currentDirection == self::North) {
            if ($action == MaqeBotAction::Left) 
                return self::West;
            else if ($action == MaqeBotAction::Right) 
                return self::East;
        }
        else if ($currentDirection == self::South) {
            if ($action == MaqeBotAction::Left) 
                return self::East;
            else if ($action == MaqeBotAction::Right) 
                return self::West;
        }
        else if ($currentDirection == self::West) {
            if ($action == MaqeBotAction::Left) 
                return self::South;
            else if ($action == MaqeBotAction::Right) 
                return self::North;
        }
        else if ($currentDirection == self::East) {
            if ($action == MaqeBotAction::Left) 
                return self::North;
            else if ($action == MaqeBotAction::Right) 
                return self::South;
        }
    }
}

class MaqeBotAction {
    const Left = "L";
    const Right = "R";
    const Walk = "W";

    static function walk($bot, $totalWalk) {
        if ($bot->direction == MaqeBotDirection::North) {
            $bot->y = $bot->y + $totalWalk;
        }
        else if ($bot->direction == MaqeBotDirection::South) {
            $bot->y = $bot->y - $totalWalk;
        }
        else if ($bot->direction == MaqeBotDirection::West) {
            $bot->x = $bot->x - $totalWalk;
        }
        else if ($bot->direction == MaqeBotDirection::East) {
            $bot->x = $bot->x + $totalWalk;
        }
    }
}

class MaqeBot {
    
    // walking result
    public $x = 0;
    public $y = 0;
    public $direction = MaqeBotDirection::North;

    private $_command;
    private $_isValidCommand = true;
    private $_isWalkCommand = false;
    private $_totalWalk = '';

    public function MaqeBot($command) {
        $this->_command = $command;
    }

    public function run() {
        foreach (str_split($this->_command) as $char) {
            if ($char == MaqeBotAction::Left || $char == MaqeBotAction::Right) {
                $this->_checkWalking();

                $direction = MaqeBotDirection::getMovement($this->direction, $char);
                if ($direction) {
                    $this->direction = $direction;
                }
            }
            else if ($char == MaqeBotAction::Walk) {
                $this->_checkWalking();

                $this->_isWalkCommand = true;
                $this->_totalWalk = '';
            }
            else if ($this->_isWalkCommand && is_numeric($char)) {
                $this->_totalWalk .= $char;
            }
            else {
                $this->_isValidCommand = false;
                break;
            }
        }

        // check for the last walking step
        $this->_checkWalking();
    }

    public function printResult() {
        if (!$this->_isValidCommand) {
            echo 'your command is invalid...';
            return;
        }

        echo 'X: '. $this->x . ' Y: ' . $this->y . ' Direction: ' . $this->direction;
    }

    function _checkWalking() {
        if (!empty($this->_totalWalk)) {
            MaqeBotAction::walk($this, intval($this->_totalWalk));
            $this->_isWalkCommand = false;
            $this->_totalWalk = '';
        }
    }

}

// start
$bot = new MaqeBot($argv[1]);
$bot->run();
$bot->printResult();
