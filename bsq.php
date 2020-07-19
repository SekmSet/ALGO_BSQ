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

function findLargestSquare($matrix) {
    $cache = $matrix;
    $result = 0;
    $x = null;
    $h = null;

    for ($i = 0; $i < count($matrix); $i++) {
        for ($j = 0; $j < count($matrix[0]); $j++) {
            if ($i === 0 | $j === 0) {}
            else if ($matrix[$i][$j] > 0) {
                $cache[$i][$j] = 1 + min($cache[$i][$j-1], $cache[$i-1][$j], $cache[$i-1][$j-1]);
            }
            if ($cache[$i][$j] > $result) {
                $result = $cache[$i][$j];

                // get coord of bigger sqare
                $x = $j;
                $h = $i;
            }
        }
    }

    return [
        'size' => $result,
        'x' => $x, // horizontal
        'h' => $h, // vertical
    ];
}

function fillLargestSquare(array $board, array $largestSquare) {
    // start at line of end sqare
    for($i = 0, $line = $largestSquare['h']; $i  < $largestSquare['size']; $i++, $line--) {
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
