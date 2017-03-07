

## Dijkstra's Algorithm 的应用

Find shortest path (actually the minium steps) to win the Soloban game.

思路：初始化，确定从箱子开始的每个节点和邻居并且全部入队(优先队列)
用一个数组 `path_weight` 记录到达某点的步数（权值和），初始化为INF
从箱子开始，计算到达邻居位置的步数，更新 `path_weight`
最终，`path_weight`中储存的全是最小值
返回 `path_weight[targetX][targetY]`
这个题简直了，，折磨我的智商
两个小时让我只做这一个题我也做不出来
可能有些地方的思路还是比较凌乱
暂时研究不下去了。。
