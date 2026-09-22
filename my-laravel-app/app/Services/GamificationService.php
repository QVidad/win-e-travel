<?php

namespace App\Services;

use App\Models\Achievement;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class GamificationService
{
    /**
     * Award XP to a user and check for level ups/achievements.
     *
     * @param User $user
     * @param int $xpAmount
     * @param string $action Optional description of what generated the XP
     * @return array Returns ['xp_awarded' => int, 'leveled_up' => bool, 'new_level' => int, 'unlocked_badges' => array]
     */
    public function awardXp(User $user, int $xpAmount, string $action = '')
    {
        $oldLevel = $user->level;
        $user->increment('xp', $xpAmount);
        
        // Dynamic Level Calculation: 1 level per 500 XP.
        // E.g. XP 0-499 = Level 1, 500-999 = Level 2.
        $newLevel = (int) floor($user->xp / 500) + 1;
        
        $leveledUp = false;
        if ($newLevel > $oldLevel) {
            $user->level = $newLevel;
            $user->save();
            $leveledUp = true;
        }

        // Evaluate all achievements
        $unlockedBadges = $this->evaluateAchievements($user);

        return [
            'xp_awarded' => $xpAmount,
            'leveled_up' => $leveledUp,
            'new_level' => $newLevel,
            'unlocked_badges' => $unlockedBadges
        ];
    }

    /**
     * Evaluate if the user meets criteria for any locked achievements.
     * 
     * @param User $user
     * @return array Array of newly unlocked Achievement objects
     */
    public function evaluateAchievements(User $user)
    {
        $unlockedBadges = [];
        
        // Get user's currently unlocked achievements
        $unlockedIds = DB::table('user_achievements')
            ->where('user_id', $user->id)
            ->pluck('achievement_id')
            ->toArray();

        // Get all achievements that are NOT yet unlocked
        $lockedAchievements = Achievement::whereNotIn('id', $unlockedIds)->get();

        foreach ($lockedAchievements as $achievement) {
            $meetsCriteria = false;

            // First, if required_xp > 0 and user has enough XP, it's a milestone unlock.
            if ($achievement->required_xp > 0 && $user->xp >= $achievement->required_xp) {
                $meetsCriteria = true;
            }

            // Custom logic based on badge code
            switch ($achievement->code) {
                case 'scholar':
                    $meetsCriteria = $this->hasCompletedPhase1($user);
                    break;
                case 'virtual-guide':
                    $meetsCriteria = $this->hasPassedTownSimulation($user);
                    break;
                case 'master-guide':
                    $meetsCriteria = $this->hasPassedFinalBoss($user);
                    break;
                case 'flawless-execution':
                    $meetsCriteria = $this->hasFlawlessExecution($user);
                    break;
                case 'speedrunner':
                    $meetsCriteria = $this->isSpeedrunner($user);
                    break;
                case 'silver-tongue':
                    $meetsCriteria = $this->hasPerfectSpeechScore($user);
                    break;
                case 'overachiever':
                    $meetsCriteria = $this->hasPerfectFinalBossScore($user);
                    break;
                case 'straight-as':
                    $meetsCriteria = $this->hasStraightAsInPhase1($user);
                    break;
                case 'unbreakable-resolve':
                    $meetsCriteria = $this->hasPassedAfterFailing($user);
                    break;
            }

            if ($meetsCriteria) {
                DB::table('user_achievements')->insert([
                    'user_id' => $user->id,
                    'achievement_id' => $achievement->id,
                    'earned_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                $unlockedBadges[] = $achievement;
            }
        }

        return $unlockedBadges;
    }

    // --- Badge Criteria Evaluators ---

    private function hasCompletedPhase1(User $user): bool
    {
        $totalFoundation = \App\Models\CourseModule::where('type', 'foundation_chapter')->count();
        if ($totalFoundation === 0) return false;

        $completed = \App\Models\ModuleProgress::where('user_id', $user->id)
            ->where('status', 'completed')
            ->whereHas('courseModule', function($q) {
                $q->where('type', 'foundation_chapter');
            })->count();
            
        return $completed >= $totalFoundation;
    }

    private function hasPassedTownSimulation(User $user): bool
    {
        return DB::table('simulation_user')
            ->join('simulations', 'simulation_user.simulation_id', '=', 'simulations.id')
            ->where('simulation_user.user_id', $user->id)
            ->where('simulation_user.passed', true)
            ->where('simulations.type', '!=', 'final')
            ->exists();
    }

    private function hasPassedFinalBoss(User $user): bool
    {
        return DB::table('simulation_user')
            ->join('simulations', 'simulation_user.simulation_id', '=', 'simulations.id')
            ->where('simulation_user.user_id', $user->id)
            ->where('simulation_user.passed', true)
            ->where('simulations.type', 'final')
            ->exists();
    }

    private function hasFlawlessExecution(User $user): bool
    {
        if (!$this->hasPassedFinalBoss($user)) return false;
        
        $hasFailed = DB::table('simulation_user')
            ->where('user_id', $user->id)
            ->where('passed', false)
            ->exists();
            
        return !$hasFailed;
    }

    private function isSpeedrunner(User $user): bool
    {
        if (!$this->hasPassedFinalBoss($user)) return false;
        
        $finalRecord = DB::table('simulation_user')
            ->join('simulations', 'simulation_user.simulation_id', '=', 'simulations.id')
            ->where('simulation_user.user_id', $user->id)
            ->where('simulation_user.passed', true)
            ->where('simulations.type', 'final')
            ->orderBy('simulation_user.created_at', 'asc')
            ->first();
            
        if (!$finalRecord) return false;
        
        return \Carbon\Carbon::parse($finalRecord->created_at)->diffInDays($user->created_at) <= 7;
    }

    private function hasPerfectSpeechScore(User $user): bool
    {
        return DB::table('simulation_user')
            ->join('simulations', 'simulation_user.simulation_id', '=', 'simulations.id')
            ->where('simulation_user.user_id', $user->id)
            ->where('simulation_user.score', '>=', 100)
            ->where('simulations.type', '!=', 'final')
            ->exists();
    }

    private function hasPerfectFinalBossScore(User $user): bool
    {
        return DB::table('simulation_user')
            ->join('simulations', 'simulation_user.simulation_id', '=', 'simulations.id')
            ->where('simulation_user.user_id', $user->id)
            ->where('simulation_user.score', '>=', 100)
            ->where('simulations.type', 'final')
            ->exists();
    }

    private function hasStraightAsInPhase1(User $user): bool
    {
        return $this->hasCompletedPhase1($user);
    }

    private function hasPassedAfterFailing(User $user): bool
    {
        $failures = DB::table('simulation_user')
            ->where('user_id', $user->id)
            ->where('passed', false)
            ->select('simulation_id', DB::raw('count(*) as fails'))
            ->groupBy('simulation_id')
            ->get();
            
        foreach ($failures as $f) {
            if ($f->fails >= 2) {
                $passed = DB::table('simulation_user')
                    ->where('user_id', $user->id)
                    ->where('passed', true)
                    ->where('simulation_id', $f->simulation_id)
                    ->exists();
                if ($passed) return true;
            }
        }
        return false;
    }
}
