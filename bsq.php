<?php

const EMPTY_VALUE_BOARD = '.';
const SQUARE_BOARD = 'x';

function generateBoard($file): array {
    $board = [];

    $handle = fopen($file, 'rb');
    if ($handle === null) {
        echo "un problème lors de l'ouverture du fichier";
        exit;
    }
    // get 1st line of file
    $numLines = (int)fgets($handle);

    while ($line = fgets($handle)) {
        // remove \n and convert to array and store new line of board
        $board[] = str_split(trim($line));
    }

    if ($numLines !== count($board)) {
        echo "Nombre de ligne invalide";
        exit;
    }

    return $board;
}

function convertToMatrix($board) {
    // fill board by 1 and 0
    foreach ($board as $line => $items){
        foreach ($items as $col => $value) {
            if ($value === EMPTY_VALUE_BOARD) {
                $board[$line][$col] = 1;
            } else {
                $board[$line][$col] = 0;
            }
        }
    }

    return $board;
}

function findLargestSquare($board) {
    $matrix = $board;
    $maxSizeSquare = 0;
    $x = null;
    $y = null;

    for ($line = 0; $line < count($board); $line++) {
        for ($col = 0; $col < count($board[0]); $col++) {
            if ($line === 0 || $col === 0) {} // do nothing
            else if ($matrix[$line][$col] > 0) {
                $matrix[$line][$col] = 1 + min($matrix[$line][$col-1], $matrix[$line-1][$col], $matrix[$line-1][$col-1]);
            }

            if ($matrix[$line][$col] > $maxSizeSquare) {
                $maxSizeSquare = $matrix[$line][$col];

                // get coord of bigger sqare
                $x = $col;
                $y = $line;
            }
        }
    }

    return [
        'size' => $maxSizeSquare,
        'x' => $x, // horizontal
        'y' => $y, // vertical
    ];
}

function fillLargestSquare(array $board, array $largestSquare) {
    // start at line of end sqare
    for($i = 0, $line = $largestSquare['y']; $i  < $largestSquare['size']; $i++, $line--) {
        // start at col of end sqare
        for($j = 0, $col = $largestSquare['x']; $j < $largestSquare['size']; $j++, $col--) {
            // fill sqare by x
            $board[$line][$col]=SQUARE_BOARD;
        }
    }

    return $board;
}

function printResult($board) {
    foreach($board as $line) {
        foreach ($line as $col) {
            echo $col;
        }
        echo PHP_EOL;
    }
}

function bsq($file) {
    $board = generateBoard($file);
    $matrix = convertToMatrix($board);
    $largestSquare = findLargestSquare($matrix);
    $newBoard = fillLargestSquare($board, $largestSquare);
    printResult($newBoard);
}

$file = $argv[1] ?? null;
if (file_exists($file)) {
    bsq($file);
} else {
    echo 'argument invalide';
}
