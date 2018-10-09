<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 08.10.2018
 * Time: 11:44
 */

namespace App\Http\Controllers;

use App;
use Illuminate\Support\Facades\DB;

class LeagueTableController extends Controller
{
    public function index()
    {
        //DB::enableQueryLog();
        if (\Request::ajax() && !empty($_POST['newTournament'])) {
            App\Info::truncate();
        }
        $currentWeek = (int) DB::table('info')
            ->join('matches', 'matches.id', '=', 'info.match')
            ->select(DB::raw('MAX(matches.week) max_week'))
            ->value('max_week');
        $weeks = 6;

        if (\Request::ajax() && $currentWeek < $weeks && empty($_POST['newTournament'])) {
            $currentWeek += 1;
        }

        $matches = App\Matches::where('week', '<=', $currentWeek)->get();
        if (!empty($_POST['playAll'])) {
            $matches = App\Matches::all();
        }

        foreach ($matches as $row) {

            $matchesCount = App\Info::where('match', $row->id)->count();

            if ($matchesCount > 0) {
                continue;
            }

            $commandInfo['command1']['match'] = $row->id;
            $commandInfo['command2']['match'] = $row->id;
            $commandInfo['command1']['command'] = $row->commands1->id;
            $commandInfo['command2']['command'] = $row->commands2->id;
            $commandInfo['command1']['goals'] = mt_rand(round(0 + mt_rand($row->commands1->rank / 2 * 10, 10) / 10), round(5 * $row->commands1->rank));
            $commandInfo['command2']['goals'] = mt_rand(round(0 + mt_rand($row->commands2->rank / 2 * 10, 10) / 10), round(5 * $row->commands2->rank));

            $commandInfo['command1']['gd'] = $commandInfo['command1']['goals'] - $commandInfo['command2']['goals'];
            $commandInfo['command2']['gd'] = $commandInfo['command2']['goals'] - $commandInfo['command1']['goals'];

            if ($commandInfo['command1']['gd'] > 0) {
                $commandInfo['command1']['win'] = 1;
                $commandInfo['command2']['win'] = 0;
                $commandInfo['command1']['lost'] = 0;
                $commandInfo['command2']['lost'] = 1;
                $commandInfo['command1']['drawn'] = 0;
                $commandInfo['command2']['drawn'] = 0;
                $commandInfo['command1']['pts'] = 3;
                $commandInfo['command2']['pts'] = 0;
            } else if ($commandInfo['command1']['gd'] < 0) {
                $commandInfo['command1']['win'] = 0;
                $commandInfo['command2']['win'] = 1;
                $commandInfo['command1']['lost'] = 1;
                $commandInfo['command2']['lost'] = 0;
                $commandInfo['command1']['drawn'] = 0;
                $commandInfo['command2']['drawn'] = 0;
                $commandInfo['command1']['pts'] = 0;
                $commandInfo['command2']['pts'] = 3;
            } else {
                $commandInfo['command1']['win'] = 0;
                $commandInfo['command2']['win'] = 0;
                $commandInfo['command1']['lost'] = 0;
                $commandInfo['command2']['lost'] = 0;
                $commandInfo['command1']['drawn'] = 1;
                $commandInfo['command2']['drawn'] = 1;
                $commandInfo['command1']['pts'] = 1;
                $commandInfo['command2']['pts'] = 1;
            }

            foreach($commandInfo as $res) {
                $info = new App\Info;
                foreach($res as $key => $res2) {
                    $info->$key = $res2;
                }
                $info->save();
            }
        }

        if ($currentWeek === 0) {
            $data = DB::table('commands')
                ->select(DB::raw('name, 0 as played, 0 as sum_goals, 0 as sum_win, 
            0 as sum_drawn, 0 as sum_lost, 0 as sum_gd, 0 as sum_pts'))
                ->orderBy('name')->get();
        } else {
            $data = DB::table('info')
                ->join('matches', 'matches.id', '=', 'info.match')
                ->join('commands', 'commands.id', '=', 'info.command')
                ->select(DB::raw('commands.name, COUNT(*) played, SUM(goals) sum_goals, SUM(win) sum_win, 
            SUM(drawn) sum_drawn, SUM(lost) sum_lost, SUM(gd) sum_gd, SUM(pts) sum_pts'))
                ->groupBy('command')->orderBy('sum_pts', 'DESC')->orderBy('sum_gd', 'DESC')->get();
        }

        $resultsForPrediction = [];
        if ($currentWeek >= 4 && $currentWeek < 6) {
            $maxSumPts = 0;
            $sumPts = 0;
            $maxSumGd = 0;
            $sumGd = 0;
            foreach($data as $row) {
                $resultsForPrediction[$row->name]['sum_pts'] =  $row->sum_pts;
                $resultsForPrediction[$row->name]['sum_gd'] =  $row->sum_gd;
                if ($row->sum_pts > $maxSumPts) {
                    $maxSumPts = $row->sum_pts;
                }
                if ($row->sum_gd > $maxSumGd) {
                    $maxSumGd = $row->sum_gd;
                }
                $sumPts += $row->sum_pts;
                $sumGd +=  $row->sum_gd;
            }

            foreach($resultsForPrediction as $key => $res) {
                $resultsForPrediction[$key]['res'] = round(($res['sum_pts'] * 0.9 + $res['sum_gd'] * 0.1) / ($sumPts * 0.9 + $sumGd * 0.1) * 100, 2);
            }

            uasort ($resultsForPrediction, [$this, 'cmp']);
            $resultsForPrediction = array_reverse($resultsForPrediction, true);
        }

        $grouppedMatches = [];
        if ($matches) {
            foreach ($matches as $match) {
                $grouppedMatches[$match->week][$match->id] = $match;
            }
        }

        if (!empty($_POST['playAll'])) {
            $currentWeek = 6;
        }

        $renderData =  [
            'data' => $data,
            'matches' => $matches,
            'grouppedMatches' => $grouppedMatches,
            'resultsForPrediction' => $resultsForPrediction,
            'currentWeek' => $currentWeek
        ];

        if (\Request::ajax()) {
            return view('leagueTable.ajax', $renderData);
        }

        //print_r(DB::getQueryLog());

        return view('leagueTable.index', $renderData);
    }

    private function cmp($a, $b)
    {
        if ($a == $b) {
            return 0;
        }
        return ($a < $b) ? -1 : 1;
    }
}
