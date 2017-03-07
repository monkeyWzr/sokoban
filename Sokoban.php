<?php

// Dijkstra's Algorithm 的应用
// 思路：初始化，确定从箱子开始的每个节点和邻居并且全部入队(优先队列)
// 用一个数组 path_weight 记录到达某点的步数（权值和），初始化为INF
// 从箱子开始，计算到达邻居位置的步数，更新path_weight
// 最终，path_weight中储存的全是最小值
// 返回path_weight[targetX][targetY]
// 这个题简直了，，折磨我的智商
// 两个小时让我只做这一个题我也做不出来
// 可能有些地方的思路还是比较凌乱
// 暂时研究不下去了。。

/**
 * Find shortest path (actually the minium steps) to win the Soloban game.
 * 'X' represents player,
 * '*' represents the box,
 * '@' represents the target point.
 * 
 * @author monkeyWzr <monkeywzr@gmail.com>
 */
class Node {
    public $steps = 0;
    public $posi = [];
    public $manPosi = [];

    public function __construct($p, $m, $s = 0) {
        $this->posi = $p;
        $this->manPosi = $m;
        $this->steps = $s;
    }
}

class Sokoban {
    /**
     * find shortest path (actually the minium steps) to win the game.
     * @param  array $map the map data
     * @param  int   $m   the map width
     * @param  int   $n   the map height
     * @return int        the minium steps
     */
    public static function findShortestPath($map, $m, $n) {
        $queue = new \SplPriorityQueue();
        $path_weight = [];
        $visited = [];
        for($i = 0; $i < $n; $i++) {
            for ($j = 0; $j < $m; $j++) {
                if ($map[$i][$j] == 'X') {
                    $manX = $i;
                    $manY = $j;
                }
                elseif ($map[$i][$j] == '*') {
                    $boxX = $i;
                    $boxY = $j;
                }
                elseif ($map[$i][$j] == '@') {
                    $targetX = $i;
                    $targetY = $j;
                }
            }
        }

        $boxNode = new Node([$boxX, $boxY], [$manX, $manY], 0);
        $path_weight[$boxX][$boxY] = 0;
        $queue->insert($boxNode, $path_weight[$boxX][$boxY]);

        while (!$queue->isEmpty()) {
            $currentNode = $queue->extract();
            self::enqueueNeighbors($map, $currentNode, $queue, $path_weight, $visited);
        }

        if (isset($path_weight[$targetX][$targetY]))
            return $path_weight[$targetX][$targetY];
        else
            return -1;

    }

    /**
     * find the minium steps to reach the position
     * @param  array $map   the map array
     * @param  array $start current position
     * @param  array $end   destination [x, y]
     * @return int          steps
     *                      -1 if cannot reach
     */
    public static function findMinSteps($map, $start, $end) {
        $queue = new SplQueue();
        $visited = $map;
        // each node represents position and steps to reach here
        // Array([x, y], steps)
        $queue->enqueue([$start, 0]);
        while (!$queue->isEmpty()) {
            $curr = $queue->dequeue();
            if ($curr[0] == $end)
                return $curr[1];
            $x = $curr[0][0];
            $y = $curr[0][1];
            $nx = [$x + 1, $x - 1, $x, $x];
            $ny = [$y, $y, $y + 1, $y - 1];
            for ($i = 0; $i < 4; $i++) {
                if (isset($map[$nx[$i]][$ny[$i]])) {
                    if ($visited[$nx[$i]][$ny[$i]] !== true && $map[$nx[$i]][$ny[$i]] === '.') {
                        $queue->enqueue([[$nx[$i], $ny[$i]], $curr[1] + 1]);
                        $visited[$nx[$i]][$ny[$i]] = true;
                    }
                }
            }
        }
        return -1;
    }

    /**
     * enqueue neighbors of current position
     * @param  array            $map          the map
     * @param  Node             $node         current node
     * @param  SplPriorityQueue &$queue       the queue
     * @param  array            &$path_weight path weights to reach each node
     * @param  array            &$visited     flag for the visiting state
     * @return void
     */
    public static function enqueueNeighbors($map, $node, &$queue, &$path_weight, &$visited) {
        $boxX = $node->posi[0];
        $boxY = $node->posi[1];
        $manX = $node->manPosi[0];
        $manY = $node->manPosi[1];
        // $visited = $map;
        $visited[$boxX][$boxY] = true;
        $boxNewX = [$boxX + 1, $boxX - 1, $boxX, $boxX];
        $boxNewY = [$boxY, $boxY, $boxY + 1, $boxY - 1];
        $manNewX = [$boxX - 1, $boxX + 1, $boxX, $boxX];
        $manNewY = [$boxY, $boxY, $boxY - 1, $boxY + 1];
        for ($i = 0; $i < 4; $i++) {
            if (isset($map[$boxNewX[$i]][$boxNewY[$i]], $map[$manNewX[$i]][$manNewY[$i]])) {
                if ($map[$boxNewX[$i]][$boxNewY[$i]] != '#' && $map[$manNewX[$i]][$manNewY[$i]] != '#') {
                    $ds = self::findMinSteps($map, [$manX, $manY], [$manNewX[$i], $manNewY[$i]]) + 1;
                    if ($ds > 0) {
                        // $steps = $node->steps + $ds;
                        $neighbor = new Node([$boxNewX[$i], $boxNewY[$i]], [$boxX, $boxY], $ds);
                        if (!isset($path_weight[$boxNewX[$i]][$boxNewY[$i]]) || $path_weight[$boxX][$boxY] + $ds < $path_weight[$boxNewX[$i]][$boxNewY[$i]])
                            $path_weight[$boxNewX[$i]][$boxNewY[$i]] = $path_weight[$boxX][$boxY] + $ds;

                        if (!isset($visited[$boxNewX[$i]][$boxNewY[$i]])) {
                            $queue->insert($neighbor, -$path_weight[$boxNewX[$i]][$boxNewY[$i]]);
                            $visited[$boxNewX[$i]][$boxNewY[$i]] = true;
                        }
                    }
                }
            }
        }
    }
}

// $map = [['.', '.', '.', '#', '.', '.'],
//         ['.', '.', '.', '.', '.', '.'],
//         ['#', '.', '#', '#', '.', '.'],
//         ['.', 'X', '#', '#', '.', '#'],
//         ['.', '*', '.', '.', '.', '.'],
//         ['.', '@', '#', '.', '.', '.']];
// $m = 6;
// $n = 6;

// echo Sokoban::findShortestPath($map, $m, $n);
